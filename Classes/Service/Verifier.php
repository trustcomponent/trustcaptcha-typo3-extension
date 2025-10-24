<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\Service;

use Trustcomponent\Trustcaptcha\Utility\Config;

final class Verifier
{
    /** @var array<string,VerificationResult> */
    private static array $requestCache = [];

    public function verifyFromPost(array $post): VerificationResult
    {
        $tokenField = Config::tokenFieldName();
        $token = trim((string)($post[$tokenField] ?? ''));
        return $this->verifyToken($token);
    }

    public function verifyToken(string $token): VerificationResult
    {
        if ($token === '') {
            return VerificationResult::failed('Please complete the CAPTCHA.');
        }

        $secret = Config::secretKey();
        if ($secret === '') {
            return VerificationResult::failed('Server secret key not configured');
        }

        $cacheKey = sha1($token);
        if (isset(self::$requestCache[$cacheKey])) {
            return self::$requestCache[$cacheKey];
        }

        try {
            if (class_exists('\\TrustComponent\\TrustCaptcha\\CaptchaManager')) {
                /** @var object $verification */
                $verification = \TrustComponent\TrustCaptcha\CaptchaManager::getVerificationResult($secret, $token);
            } else {
                return VerificationResult::failed('TrustCaptcha PHP library not found');
            }

            $passed = (bool)($verification->verificationPassed ?? false);
            $score  = (float)($verification->score ?? 1.0);
            $threshold = Config::threshold();

            $ok = $passed && $score <= $threshold;

            $result = new VerificationResult(
                $ok,
                $passed,
                $score,
                $ok ? null : 'We could not confirm you are human. Please try again later.'
            );

            self::$requestCache[$cacheKey] = $result;
            return $result;
        } catch (\Throwable $e) {
            if (str_contains($e->getMessage(), '410')) {
                return VerificationResult::failed('Please solve the CAPTCHA again.');
            }
            return VerificationResult::failed('Verification exception: ' . $e->getMessage());
        }
    }
}

final class VerificationResult
{
    public bool $accepted;
    public bool $verificationPassed;
    public float $score;
    public ?string $error;

    public function __construct(bool $accepted, bool $verificationPassed, float $score, ?string $error = null)
    {
        $this->accepted = $accepted;
        $this->verificationPassed = $verificationPassed;
        $this->score = $score;
        $this->error = $error;
    }

    public static function failed(string $reason): self
    {
        return new self(false, false, 1.0, $reason);
    }
}

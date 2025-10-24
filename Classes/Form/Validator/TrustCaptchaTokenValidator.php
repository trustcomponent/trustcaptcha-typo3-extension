<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\Form\Validator;

use Trustcomponent\Trustcaptcha\Service\Verifier;
use Trustcomponent\Trustcaptcha\Utility\Config;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

final class TrustCaptchaTokenValidator extends AbstractValidator
{
    protected $acceptsEmptyValues = false;

    public function isValid($value): void
    {
        $fieldName = Config::tokenFieldName();
        $token = is_string($value) ? trim($value) : '';

        if ($token === '' && isset($_POST[$fieldName])) {
            $token = trim((string)$_POST[$fieldName]);
        }

        /** @var Verifier $verifier */
        $verifier = GeneralUtility::makeInstance(Verifier::class);
        $result = $verifier->verifyToken($token);

        if (!$result->accepted) {
            $msg = $result->error ?: 'Captcha verification failed. Please try again.';
            $this->addError($msg, 1700001001);
        }
    }
}

<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\Powermail\Validator;

use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermail\Domain\Validator\AbstractValidator;
use Trustcomponent\Trustcaptcha\Service\Verifier;
use Trustcomponent\Trustcaptcha\Utility\Config;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;

final class TrustCaptchaValidator extends AbstractValidator
{
    private string $captchaFieldType = 'trustcaptcha';

    public function isValid(mixed $value): void
    {
        if ($value instanceof Mail) {
            $res = $this->validate($value);
            foreach ($res->getErrors() as $error) {
                $this->result->addError($error);
            }
        }
    }

    public function validate($mail): Result
    {
        $result = new Result();

        if (!$mail instanceof Mail) {
            return $result;
        }

        $marker = $this->findTrustCaptchaMarker($mail);
        if ($marker === null) {
            return $result;
        }

        $tokenFieldName = Config::tokenFieldName();
        $post = $_POST ?? [];
        $token = trim((string)($post[$tokenFieldName] ?? ''));

        /** @var Verifier $verifier */
        $verifier = GeneralUtility::makeInstance(Verifier::class);
        $verification = $verifier->verifyToken($token);

        if (!$verification->accepted) {
            $message = $verification->error ?: 'Captcha verification failed. Please try again.';
            $result->addError(new Error($message, 1700002001, ['marker' => $marker]));
        }

        return $result;
    }

    private function findTrustCaptchaMarker(Mail $mail): ?string
    {
        $form = $mail->getForm();
        if ($form === null) {
            return null;
        }
        $pages = $form->getPages();
        if ($pages === null) {
            return null;
        }

        foreach ($pages as $page) {
            $fields = $page->getFields();
            if ($fields === null) {
                continue;
            }
            foreach ($fields as $field) {
                if (method_exists($field, 'getType') && $field->getType() === $this->captchaFieldType) {
                    if (method_exists($field, 'getMarker')) {
                        $marker = (string)$field->getMarker();
                        return $marker !== '' ? $marker : 'trustcaptcha';
                    }
                    return 'trustcaptcha';
                }
            }
        }
        return null;
    }
}

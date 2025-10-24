<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\Powermail\SpamShield\Breaker;

use In2code\Powermail\Domain\Validator\SpamShield\Breaker\AbstractBreaker;

final class TrustCaptchaBreaker extends AbstractBreaker
{
    private string $captchaFieldType = 'trustcaptcha';

    public function isDisabled(): bool
    {
        $mail = $this->getMail();
        $form = $mail->getForm();
        if ($form === null || $form->getPages() === null) {
            return false;
        }

        foreach ($form->getPages() as $page) {
            $fields = $page->getFields();
            if ($fields === null) {
                continue;
            }
            foreach ($fields as $field) {
                if (method_exists($field, 'getType') && $field->getType() === $this->captchaFieldType) {
                    return true;
                }
            }
        }
        return false;
    }
}

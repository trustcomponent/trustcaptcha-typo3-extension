<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\Authentication;

use Trustcomponent\Trustcaptcha\Service\Verifier;
use Trustcomponent\Trustcaptcha\Utility\Config;
use TYPO3\CMS\Core\Authentication\AuthenticationService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class FrontendTrustCaptchaAuthService extends AuthenticationService
{
    public function authUser(array $user): int
    {
        if (!Config::integrateLogin()) {
            return 100;
        }

        $isLoginAttempt = isset($_POST['logintype']) && $_POST['logintype'] === 'login';
        if (!$isLoginAttempt) {
            return 100;
        }

        $verifier = GeneralUtility::makeInstance(Verifier::class);
        $result = $verifier->verifyFromPost($_POST);

        if ($result->accepted) {
            return 0;
        }

        return -1;
    }
}

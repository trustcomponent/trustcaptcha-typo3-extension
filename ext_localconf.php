<?php
defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

call_user_func(function () {

    GeneralUtility::makeInstance(IconRegistry::class)->registerIcon(
        'trustcaptcha',
        SvgIconProvider::class,
        ['source' => 'EXT:trustcaptcha/Resources/Public/Icons/trustcaptcha.svg']
    );

    if (ExtensionManagementUtility::isLoaded('form')) {
        ExtensionManagementUtility::addTypoScriptSetup("@import 'EXT:form/Configuration/TypoScript/setup.typoscript'");

        $yaml = 'EXT:trustcaptcha/Configuration/Form/TrustCaptcha.form.yaml';
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['form']['yamlConfigurations'][] = $yaml;
        $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['form']['formEditor']['yamlConfigurations'][] = $yaml;

        ExtensionManagementUtility::addTypoScriptSetup(<<<'TS'
plugin.tx_form {
  settings.yamlConfigurations {
    9910 = EXT:trustcaptcha/Configuration/Form/TrustCaptcha.form.yaml
  }
}
TS);

        ExtensionManagementUtility::addTypoScriptSetup(<<<'TS'
module.tx_form {
  settings.yamlConfigurations {
    9910 = EXT:trustcaptcha/Configuration/Form/TrustCaptcha.form.yaml
  }
}
TS);
    }

    /*
    ExtensionManagementUtility::addService(
        'trustcaptcha',
        'auth',
        \Trustcomponent\Trustcaptcha\Authentication\FrontendTrustCaptchaAuthService::class,
        [
            'title' => 'TrustCaptcha Frontend Authentication',
            'description' => 'Blocks FE logins if TrustCaptcha verification fails.',
            'subtype' => 'authUserFE',
            'available' => true,
            'priority' => 75,
            'quality' => 75,
            'os' => '',
            'exec' => '',
            'className' => \Trustcomponent\Trustcaptcha\Authentication\FrontendTrustCaptchaAuthService::class,
        ]
    );
    */

    if (ExtensionManagementUtility::isLoaded('powermail')) {
        $importIfFileExists = static function (string $extPath, bool $constants = false): void {
            $abs = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($extPath);
            if ($abs && @is_file($abs)) {
                if ($constants) { ExtensionManagementUtility::addTypoScriptConstants("@import '" . $extPath . "'"); }
                else { ExtensionManagementUtility::addTypoScriptSetup("@import '" . $extPath . "'"); }
            }
        };
        $importIfFileExists('EXT:powermail/Configuration/TypoScript/Main/constants.typoscript', true);
        $importIfFileExists('EXT:powermail/Configuration/TypoScript/Main/setup.typoscript', false);
        $importIfFileExists('EXT:powermail/Configuration/TypoScript/constants.typoscript', true);
        $importIfFileExists('EXT:powermail/Configuration/TypoScript/setup.typoscript', false);
    }

    ExtensionManagementUtility::addTypoScript('trustcaptcha', 'setup', "@import 'EXT:trustcaptcha/Configuration/TypoScript/powermail.typoscript'");
    ExtensionManagementUtility::addTypoScript('trustcaptcha', 'setup', "@import 'EXT:trustcaptcha/Configuration/TypoScript/powermail_viewpaths.typoscript'");
    ExtensionManagementUtility::addTypoScript('trustcaptcha', 'setup', "@import 'EXT:trustcaptcha/Configuration/TypoScript/form_viewpaths.typoscript'");

    ExtensionManagementUtility::addPageTSConfig(
        "@import 'EXT:trustcaptcha/Configuration/PageTS/powermail.tsconfig'"
    );
});

<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\Utility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class Config
{
    private const EXTENSION_KEY = 'trustcaptcha';

    public static function all(): array
    {
        $defaults = [
            'siteKey' => '',
            'secretKey' => '',
            'licenseKey' => '',
            'threshold' => 0.5,
            'width' => 'full',
            'language' => 'auto',
            'theme' => 'light',
            'autostart' => true,
            'invisible' => false,
            'invisibleHint' => 'inline',
            'hideBranding' => false,
            'privacyUrl' => '',
            'customDesignJson' => '',
            'customTranslationsJson' => '',
            'mode' => 'standard',
            'bypassToken' => '',
            'failoverEnabled' => false,
            'integrateLogin' => false,
            'tokenFieldName' => 'tc-verification-token',
        ];

        try {
            $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(self::EXTENSION_KEY);
            if (!is_array($extConf)) {
                return $defaults;
            }

            $out = $defaults;
            foreach ($extConf as $k => $v) {
                $out[$k] = $v;
            }

            $out['tokenFieldName'] = 'tc-verification-token';
            $out['autostart'] = (string)($out['autostart'] ?? '1') !== '0';
            $out['invisible'] = (string)($out['invisible'] ?? '0') === '1';
            $out['hideBranding'] = (string)($out['hideBranding'] ?? '0') === '1';
            $out['failoverEnabled'] = (string)($out['failoverEnabled'] ?? '0') === '1';
            $out['integrateLogin'] = (string)($out['integrateLogin'] ?? '0') === '1';
            // Clamp threshold to the new 0.2 floor.
            $out['threshold'] = max(0.2, (float)($out['threshold'] ?? 0.5));
            $out['bypassToken'] = (string)($out['bypassToken'] ?? '');
            $out['mode'] = (string)($out['mode'] ?? 'standard');
            $out['width'] = (string)($out['width'] ?? 'full');
            $out['privacyUrl'] = (string)($out['privacyUrl'] ?? '');
            $out['invisibleHint'] = (string)($out['invisibleHint'] ?? 'inline');

            return $out;
        } catch (\Throwable $e) {
            return $defaults;
        }
    }

    public static function threshold(): float
    {
        return (float) (self::all()['threshold'] ?? 0.5);
    }

    public static function siteKey(): string
    {
        return (string) (self::all()['siteKey'] ?? '');
    }

    public static function secretKey(): string
    {
        return (string) (self::all()['secretKey'] ?? '');
    }

    public static function failoverEnabled(): bool
    {
        return (bool) (self::all()['failoverEnabled'] ?? false);
    }

    public static function integrateLogin(): bool
    {
        return (bool) (self::all()['integrateLogin'] ?? false);
    }

    public static function tokenFieldName(): string
    {
        return 'tc-verification-token';
    }
}

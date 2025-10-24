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
            'language' => 'auto',
            'theme' => 'light',
            'autostart' => true,
            'invisible' => false,
            'hideBranding' => false,
            'customDesignJson' => '',
            'customTranslationsJson' => '',
            'bypassToken' => '',
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
            $out['integrateLogin'] = (string)($out['integrateLogin'] ?? '0') === '1';
            $out['threshold'] = (float)($out['threshold'] ?? 0.5);
            $out['bypassToken'] = (string)($out['bypassToken'] ?? '');

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

    public static function integrateLogin(): bool
    {
        return (bool) (self::all()['integrateLogin'] ?? false);
    }

    public static function tokenFieldName(): string
    {
        return 'tc-verification-token';
    }
}

<?php
declare(strict_types=1);

defined('TYPO3') or die();

call_user_func(function () {
    if (!\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('powermail')) return;
    if (!isset($GLOBALS['TCA']['tx_powermail_domain_model_field'])) return;
    $GLOBALS['TCA']['tx_powermail_domain_model_field']['types']['trustcaptcha'] = [
        'showitem' => implode(',', [
            '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general',
            'title',
            'type',
            '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended',
            'description',
            '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access',
            'hidden, starttime, endtime, fe_group',
        ]),
        'columnsOverrides' => [
            'marker' => [
                'config' => [
                    'readOnly' => true,
                ],
            ],
        ],
    ];
});

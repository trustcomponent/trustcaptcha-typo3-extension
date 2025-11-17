<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'TrustCaptcha (GDPR-compliant CAPTCHA for TYPO3)',
    'description' => 'Privacy-first CAPTCHA solution for TYPO3. GDPR-compliant, bot protection made in Europe.',
    'category' => 'fe',
    'author' => 'TrustCaptcha GmbH',
    'author_email' => 'mail@trustcomponent.com',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '2.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-13.9.99',
            'php'   => '7.4.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [
            'form'      => '11.5.0-13.9.99',
            'powermail' => '9.0.0-13.99.99',
        ],
    ],
];

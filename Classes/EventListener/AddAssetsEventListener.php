<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\EventListener;

use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\Event\BeforeJavaScriptsRenderingEvent;

final class AddAssetsEventListener
{
    private const WIDGET_ESM_URL = 'https://cdn.trustcomponent.com/trustcaptcha/2.1.x/trustcaptcha.esm.min.js';

    public function __invoke(BeforeJavaScriptsRenderingEvent $event): void
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if ($request && ApplicationType::fromRequest($request)->isBackend()) {
            return;
        }

        $assetCollector = $event->getAssetCollector();
        if ($assetCollector instanceof AssetCollector) {
            $assetCollector->addJavaScript(
                'trustcaptcha-cdn',
                self::WIDGET_ESM_URL,
                ['type' => 'module'],
                ['priority' => true]
            );
        }
    }
}

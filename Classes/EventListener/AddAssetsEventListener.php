<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\EventListener;

use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\Event\BeforeJavaScriptsRenderingEvent;

final class AddAssetsEventListener
{
    public function __invoke(BeforeJavaScriptsRenderingEvent $event): void
    {
        $request = $GLOBALS['TYPO3_REQUEST'] ?? null;
        if ($request && ApplicationType::fromRequest($request)->isBackend()) {
            return;
        }

        $assetCollector = $event->getAssetCollector();
        if (!$assetCollector instanceof AssetCollector) {
            return;
        }

        $assetCollector->addJavaScript(
            'trustcaptcha-widget',
            'EXT:trustcaptcha/Resources/Public/JavaScript/trustcaptcha-3.0.1.umd.min.js',
            [],
            ['priority' => true]
        );
    }
}

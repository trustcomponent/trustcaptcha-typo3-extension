<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\EventListener;

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Event\PolicyMutatedEvent;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;

final class ContentSecurityPolicyEventListener
{
    private const CDN = 'https://cdn.trustcomponent.com/';
    private const API = 'https://api.trustcomponent.com/';

    public function __invoke(PolicyMutatedEvent $event): void
    {
        if (!$event->scope->type->isFrontend()) {
            return;
        }

        $policy = $event->getCurrentPolicy();
        $policy->extend(Directive::ScriptSrc, new UriValue(self::CDN));
        $policy->extend(Directive::ScriptSrcElem, new UriValue(self::CDN));
        $policy->extend(Directive::ConnectSrc, new UriValue(self::API));
        $event->setCurrentPolicy($policy);
    }
}

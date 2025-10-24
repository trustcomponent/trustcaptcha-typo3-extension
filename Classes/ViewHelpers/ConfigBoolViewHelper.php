<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\ViewHelpers;

use Trustcomponent\Trustcaptcha\Utility\Config;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class ConfigBoolViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('property', 'string', 'Extension setting key (bool)', true);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): string
    {
        $prop = (string)$arguments['property'];
        $cfg  = Config::all();
        $val  = (bool)($cfg[$prop] ?? false);
        return $val ? 'true' : 'false';
    }
}

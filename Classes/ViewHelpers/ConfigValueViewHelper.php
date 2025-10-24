<?php
declare(strict_types=1);

namespace Trustcomponent\Trustcaptcha\ViewHelpers;

use Trustcomponent\Trustcaptcha\Utility\Config;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

final class ConfigValueViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('property', 'string', 'Extension setting key', true);
        $this->registerArgument('default', 'mixed', 'Default value if key is missing', false, '');
    }
    
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $cfg  = Config::all();
        $prop = (string)$arguments['property'];
        $def  = $arguments['default'];
        $val  = $cfg[$prop] ?? $def;

        if (is_bool($val)) {
            return $val ? 'true' : 'false';
        }
        if (is_scalar($val)) {
            return (string)$val;
        }
        return (string)($val ?? '');
    }
}

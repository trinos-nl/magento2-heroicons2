<?php

namespace Trinos\Heroicons2\ViewModel;

use Hyva\Theme\ViewModel\SvgIcons;

abstract class Heroicons2Base extends SvgIcons implements Heroicons2Interface
{
    use HeroiconsCompat;

    /**
     * Magic method to allow iconNameHtml() instead of renderHtml('icon-name'). Subclasses may
     * use `@method` doc blocks to provide autocompletion for available icons.
     */
    public function __call($method, $args)
    {
        if (\preg_match('/^(.*)Html$/', $method, $matches)) {
            return $this->renderHtml(self::camelCaseToKebabCase($matches[1]), ...$args);
        }
        return '';
    }

    /**
     * Convert a CamelCase string into kebab-case
     *
     * For example ArrowUp => arrow-up
     */
    private static function camelCaseToKebabCase(string $str): string
    {
        return str_replace('--', '-', strtolower(preg_replace('/(.|[0-9])([A-Z]|[0-9])?([A-Z]|[0-9])/', "$1-$2-$3", $str)));
    }
}

<?php

namespace Lupsor\ArrayParser;

use ArrayIterator;

class Parser extends ArrayIterator
{
    protected static string $separator = '->';

    public static function generate(string $parserString): self
    {
        return new self(self::parse($parserString));
    }

    private static function parse(string $string): array
    {
        $startArray = strpos($string, self::$separator . '[');
        if ($startArray === false) {
            return explode(self::$separator, $string);
        }
        $parser = explode(self::$separator, substr($string, 0, $startArray));

        if (!empty($array = substr($string, $startArray + 3, -1))) {
            $parser[] = self::generate($array);
        }
        return $parser;
    }
}

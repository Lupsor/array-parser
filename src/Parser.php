<?php

namespace Lupsor\ArrayParser;

use ArrayIterator;

class Parser extends ArrayIterator
{
    protected static string $arrayStartCharacter = '[';

    protected static string $separator = '->';

    public static function generate(string $parserString): self
    {
        return new self(self::parse($parserString));
    }

    private static function parse(string $string): array
    {
        $arrayStartCombination = self::$separator . self::$arrayStartCharacter;
        $arrayPosition = strpos($string, $arrayStartCombination);
        if ($arrayPosition === false) {
            return explode(self::$separator, $string);
        }
        $parser = explode(self::$separator, substr($string, 0, $arrayPosition));

        $array = substr($string, $arrayPosition + strlen($arrayStartCombination), -1);
        if (!empty($array)) {
            $parser[] = self::generate($array);
        }
        return $parser;
    }
}

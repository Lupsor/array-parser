<?php

namespace Lupsor\ArrayParser;

class ArrayParser
{
    protected static string $valueSeparator = ', ';

    public static function parse(array $data, string $parserString): mixed
    {
        return self::get($data, Parser::generate($parserString));
    }

    private static function get(array $data, Parser $parser): mixed
    {
        $value = null;
        foreach ($parser as $parserItem) {
            if ($parserItem instanceof Parser) {
                $concatValues = [];
                foreach ($data as $item) {
                    $concatValues[] = self::get($item, $parserItem);
                }
                $value = array_diff($concatValues, [null]);
                break;
            }
            if ($parser->valid()) {
                $value = $data[$parserItem];
            }
            $data = $data[$parserItem];
        }

        return is_array($value)
            ? implode(self::$valueSeparator, $value)
            : $value;
    }
}

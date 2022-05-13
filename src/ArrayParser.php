<?php

namespace Lupsor\ArrayParser;

class ArrayParser
{
    public static function parse(array $data, string $parserString)
    {
        return self::get($data, Parser::generate($parserString));
    }

    private static function get(array $data, Parser $parser)
    {
        $value = null;
        if (count($parser) === 1) {
            foreach ($parser as $parserItem) {
                return $data[$parserItem];
            }
        }
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
            ? implode(', ', $value)
            : $value;
    }
}

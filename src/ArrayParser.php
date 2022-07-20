<?php

namespace Lupsor\ArrayParser;

use Lupsor\ArrayParser\Exception\ParseException;

class ArrayParser
{
    protected static string $valueSeparator = ', ';

    /**
     * @param array|object $data
     * @param string $parserString
     * @return mixed|string|null
     */
    public static function parse($data, string $parserString)
    {
        return self::get($data, Parser::generate($parserString));
    }

    /**
     * @param array|object $data
     * @param Parser $parser
     * @return mixed|string|null
     */
    private static function get($data, Parser $parser)
    {
        $value = null;
        foreach ($parser as $parserItem) {
            if ($parserItem instanceof Parser) {
                $concatValues = [];
                if (!empty($data)) {
                    foreach ($data as $item) {
                        $concatValues[] = self::get($item, $parserItem);
                    }
                }
                $value = array_diff($concatValues, [null]);
                break;
            }
            if ($parser->valid()) {
                $value = self::getDataValue($data, $parserItem);
            }
            $data = self::getDataValue($data, $parserItem);
        }

        if ($parser::$unique && is_array($value)) {
            $value = array_unique($value);
        }

        return is_array($value)
            ? implode(self::$valueSeparator, $value)
            : $value;
    }

    /**
     * @param array|object $data
     * @param string $key
     * @return mixed|null
     */
    private static function getDataValue($data, string $key)
    {
        $result = null;
        if (is_object($data) && method_exists($data, $key)) {
            try {
                $result = $data->$key();
                if (is_object($result) && method_exists($result, '__toString')) {
                    $result = $result->__toString();
                } elseif (is_object($result)) {
                    throw new ParseException();
                }
            } catch (ParseException $exception) {}
        }
        if (!empty($result)) {
            return $result;
        }
        return array_key_exists($key, $data) ? $data[$key] : null;
    }
}

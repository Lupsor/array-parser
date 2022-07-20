<h1 align="center">Lupsor Array Parser</h1>

<p align="center">
<a href="https://packagist.org/packages/lupsor/array-parser"><img src="https://img.shields.io/packagist/dt/lupsor/array-parser" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/lupsor/array-parser"><img src="https://img.shields.io/packagist/v/lupsor/array-parser" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/lupsor/array-parser"><img src="https://img.shields.io/packagist/l/lupsor/array-parser" alt="License"></a>
</p>

## About package

This package get data from an associative array.

Knowing the path to the data you need in the array, you can easily get it using `ArrayParser`, without having to iterate through the array yourself.

## Содержание

1. [Setup](#setup)
2. [Using](#using)
   1. [ParserString](#parserString)
      1. [Without collection](#withoutCollection)
      2. [With collection](#withCollection)
   2. [Parse object](#parseObject)
   3. [Examples of using](#examples)
3. [Additionally](#additionally)
   1. [Unique values](#unique)

## Setup<a name="setup"></a>

To install the package, run on the command line:
```
composer install lupsor/array-parser
```

## Using<a name="using"></a>

To get the desired value, run:
`ArrayParser::parse($data, $parserString)`, where `$date` is an array from which you want to get the value, and `$parserString` is a link to the received data

### ParserString<a name="parserString"></a>
#### Without collection<a name="withoutCollection"></a>

Before you get the necessary data, you need to form a link to the data you need. Example: 

`three->one->one`, where `three`, `one` and `one` - are the array key, and `->` is the separator.

Based on this example, the `ArrayParser` will refer to the array element with the key `three`, there it will refer to the element with the key `one` and it will get the value of the element with the key `one`.

#### With collection<a name="withCollection"></a>

You can also get data from the collection by wrapping the required value in square brackets (`[`, `]`). Example:

`three->[one]`, where `[one]` - collection. The parser will iterate over all the values in the element with the key "three" and from each element of the array will get the value with the key "one".

The collection can be more complex, like `[one->two]`. You can learn more about this structure in example No. 2 and 3.

### Parse object<a name="parseObject"></a>

The first argument `ArrayParser::parse` (`$data`) can be sent as an object, in which case the path must only point to functions that require no arguments.

### Examples of using<a name="examples"></a>

#### Example 1 (without collection)
```phpt
use Lupsor\ArrayParser\ArrayParser;

$data = [
    'one' => 'oneValue',
    'three' => [
        'one' => ['one' => 'threeOneOneValue',],
    ],
];
$parserString = 'three->one->one';

ArrayParser::parse($data, $parserString);
```
The result of running this example will be: ``threeOneOneValue``

#### Example 2 (with collection)
```phpt
use Lupsor\ArrayParser\ArrayParser;

$data = [
    'four' => [
        ['one' => 'fourOneValue',],
        ['one' => 'fourTwoValue',],
    ],
];
$parserString = 'four->[one]';

ArrayParser::parse($data, $parserString);
```
Result: `fourOneValue, fourTwoValue`

#### Example 3 (with collection)
```phpt
use Lupsor\ArrayParser\ArrayParser;

$data = [
    'five' => [
        [
            'one' => [
                ['one' => 'fiveOneOneValue',],
                ['one' => 'fourOneTwoValue',],
            ],
        ],
        [
            'one' => [
                ['one' => 'fiveTwoOneValue',],
                ['one' => 'fourTwoTwoValue',],
                ['one' => 'fourTwoThreeValue',],
            ],
        ],
    ],
];
$parserString = 'five->[one->[one]]';

ArrayParser::parse($data, $parserString);
```
Result: `fiveOneOneValue, fourOneTwoValue, fiveTwoOneValue, fourTwoTwoValue, fourTwoThreeValue`

## Additionally<a name="additionally"></a>
### Unique values<a name="unique"></a>

You can return only unique values by adding `:unique` at the end of `ParserString`. Example: `five->[one->[one]]:unique`

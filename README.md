# phpunit-xpath-assertions

[![CI](https://github.com/ThomasWeinert/phpunit-xpath-assertions/actions/workflows/ci.yml/badge.svg)](https://github.com/ThomasWeinert/phpunit-xpath-assertions/actions/workflows/ci.yml)
[![License](https://img.shields.io/packagist/l/thomasweinert/phpunit-xpath-assertions.svg)](https://github.com/thomasweinert/phpunit-xpath-assertions/blob/master/LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/thomasweinert/phpunit-xpath-assertions.svg)](https://packagist.org/packages/thomasweinert/phpunit-xpath-assertions)
[![Latest Stable Version](https://img.shields.io/packagist/v/thomasweinert/phpunit-xpath-assertions.svg)](https://packagist.org/packages/thomasweinert/phpunit-xpath-assertions)

Xpath assertions and constraints for use with PHPUnit.

## Example

```php
use PHPUnit\Framework\TestCase;
use PHPUnit\Xpath\Assert as XpathAssertions;

class MyProjectExampleTest extends TestCase
{
    use XpathAssertions;

    public function testChildElementExistsInDocument()
    {
        $document = new \DOMDocument();
        $document->loadXML('<root><child>TEXT</child></root>');

        self::assertXpathMatch('//child', $document);
    }

    public function testCompareChildElementFromDocument()
    {
        $document = new \DOMDocument();
        $document->loadXML('<root><child>TEXT</child></root>');

        self::assertXpathEquals('<child>TEXT</child>', '//child', $document);
    }
}
```

## Installation

### Phar

If you're using PHPUnit as a PHAR, you can download this extension as PHAR, too. They the are available on the
[release page](https://github.com/ThomasWeinert/phpunit-xpath-assertions/releases). Download the extension `*.phar` file
into a directory and provide the directory in the PHPUnit configuration file.

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd"
         extensionsDirectory="tools/phpunit.d">
</phpunit>
```

### Composer

If you use [Composer](https://getcomposer.org/) to manage the dependencies of your project then you can add the PHPUnit example extension as a development-time dependency to your project:

```
$ composer require --dev thomasweinert/phpunit-xpath-assertions
```

## Usage

The library provides traits that you can use to add the assertions to your TestCase.

```php
use PHPUnit\Xpath\Assert as XpathAssertions;
use PHPUnit\Xpath\Constraint as XpathConstraints;

class MyProjectExampleTest extends \PHPUnit\Framework\TestCase
{
    use XpathAssertions;
    use XpathConstraints;
}
```

### Constraints

Use trait `PHPUnit\Xpath\Constraint`. They can be used with `assertThat()` or
with Mocks.

#### self::matchesXpathExpression()

```php
function matchesXpathExpression(string $expression, array|\ArrayAccess $namespaces = [])
```

Validate if the provided Xpath expression matches something that is TRUE and not empty.
It will fail if the expression returns an empty node list or an empty string or FALSE.

```php
public function testChildElementExistsInDocument()
{
    $document = new \DOMDocument();
    $document->loadXML('<root><child>TEXT</child></root>');

    self::assertThat(
        $document,
        self::matchesXpathExpression('//child')
    );
}
```

#### self::matchesXpathResultCount()

```php
function matchesXpathResultCount(
    int $expectedCount, string $expression, array|\ArrayAccess $namespaces = array()
)
```

Returns true if the provided Xpath expression matches exactly the expected count of nodes.

```php
public function testChildElementExistsOnTimeInDocument()
{
    $document = new \DOMDocument();
    $document->loadXML('<root><child>TEXT</child></root>');

    self::assertThat(
        $document,
        self::matchesXpathResultCount(1, '//child')
    );
}
```

#### self::equalToXpathResult()

```php
function equalToXpathResult(
    mixed $expected,
    string $expression,
    array|\ArrayAccess,
    $namespaces = array()
)
```

If the expressions return a node list it compares the serialized XML of the matched nodes with the provided XML string
or DOM. If the expression return a scalar uses a constraint depending on the type.

```php
public function testCompareChildElementFromDocument()
{
    $document = new \DOMDocument();
    $document->loadXML('<root><child>TEXT</child></root>');

    self::assertThat(
        $document,
        self::equalToXpathResult(
            '<child>TEXT</child>',
            '//child'
        )
    );
}
```


```php
public function testCompareChildElementFromDocumentAsString()
{
    $document = new \DOMDocument();
    $document->loadXML('<root><child>TEXT</child></root>');

    self::assertThat(
        $document,
        self::equalToXpathResult(
            'TEXT',
            'string(//child)'
        )
    );
}
```

### Assertions

Use trait `PHPUnit\Xpath\Assert`. These assertions are shortcuts for
`assertThat()`.

* self::assertXpathMatch()
* self::assertXpathCount()
* self::assertXpathEquals()

### Namespaces

All methods have an optional argument that allow to provide an namespace definition.

```php
public function testChildWithNamespaceElementExistsTwoTimesInDocument()
{
    $document = new \DOMDocument();
    $document->loadXML(
        '<example:root xmlns:example="urn:example">
        <example:child>TEXT</example:child>
        <example:child>TEXT</example:child>
        </example:root>'
    );

    self::assertThat(
        $document,
        self::matchesXpathResultCount(2, '//e:child', ['e' => 'urn:example'])
    );
}
```

### JSON (>= 1.2.0)

The assertions can be used with JsonSerializable objects/arrays. They will be
converted into a DOM representation internally.

```php
public function testHomePhoneNumbersEqualsExpected()
{
    self::assertXpathEquals(
        [
            [ 'type' => 'home', 'number' => '212 555-1234' ]
        ],
        'phoneNumbers/*[type="home"]',
        json_decode($wikipediaJsonExample)
    );
}
```

# Contributing

Contributions are welcome, please use the issue tracker to report bug and feature ideas.

## Build a modified phar

This project includes build scripts to create your own phar file.
To create a phar call `./build/build-phar`.

### Build a signed phar

To create a signed phar copy the `dist.build.properties` to `build.properties` and
set the `gpg.user`. After that call `./build/build-phar package`.

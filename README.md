# phpunit-xpath-assertions

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

### Composer

If you use [Composer](https://getcomposer.org/) to manage the dependencies of your project then you can add the PHPUnit example extension as a development-time dependency to your project:

```
$ composer require --dev thomasweinert/phpunit-xpath-assertions
```

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

## Usage

### Constraints

Use trait `PHPUnit\Xpath\Constraint`. They can be used with `assertThat()` or 
with Mocks.

#### self::matchesXpathExpression()
#### self::matchesXpathResult()
#### self::matchesXpathResultCount()

### Assertions

Use trait `PHPUnit\Xpath\Assert`. These assertions are shortcuts for 
`assertThat()`.

#### self::assertXpathMatch()
#### self::assertXpathCount()
#### self::assertXpathEquals()

# PHPUnit Xpath Assertions

Xpath assertions and constraints for PHPUnit. 

## Example

```php
class MyProjectExampleTest extends \PHPUnit\Framework\TestCase
{
    use \PHPUnit\Xpath\Assert;

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
class MyProjectExampleTest extends \PHPUnit\Framework\TestCase
{
    use \PHPUnit\Xpath\Assert;
    use \PHPUnit\Xpath\Constraint;
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

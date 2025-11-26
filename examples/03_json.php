<?php

class MyProjectExampleTest extends \PHPUnit\Framework\TestCase
{
    use \PHPUnit\Xpath\Assert;

    private const JSON = <<<JSON
{
  "firstName": "John",
  "lastName": "Smith",
  "age": 25,
  "address": {
    "streetAddress": "21 2nd Street",
    "city": "New York",
    "state": "NY",
    "postalCode": 10021
  },
  "phoneNumbers": [
    {
      "type": "home",
      "number": "212 555-1234"
    },
    {
      "type": "fax",
      "number": "646 555-4567"
    }
  ]
}
JSON;

    private $_jsonContext;


    public function setUp() {
        $this->_jsonContext = json_decode(self::JSON);
    }

    public function testAddressKeyExistsInDocument()
    {
        self::assertXpathMatch('address', $this->_jsonContext);
    }

    public function testHasAddressInNewYork()
    {
        self::assertXpathMatch('address[city = "New York"]', $this->_jsonContext);
    }

    public function testDocumentHasTwoPhoneNumbers()
    {
        self::assertXpathCount(2, 'phoneNumbers/*', $this->_jsonContext);
    }

    public function testCityIsNewYork()
    {
        self::assertXpathEquals('New York', 'string(address/city)', $this->_jsonContext);
    }

    public function testPostalCodeIsNumber()
    {
        self::assertXpathMatch( 'address/postalCode[@type = "number"]', $this->_jsonContext);
    }

    public function testHomePhoneNumbersEqualsExpected()
    {
        self::assertXpathEquals(
            [
                [ 'type' => 'home', 'number' => '212 555-1234' ]
            ],
            'phoneNumbers/*[type="home"]',
            $this->_jsonContext
        );
    }
}

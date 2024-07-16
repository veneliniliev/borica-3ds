<?php
/*
 * Copyright (c) 2024. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds\Tests\Unit;

use PHPUnit\Framework\TestCase;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\SaleRequest;

class SaleRequestMInfoTest extends TestCase
{
    /**
     * @throws ParameterValidationException
     */
    public function testGetMInfo()
    {
        $request = new  SaleRequest();
        $request->setMInfo([
            'cardholderName' => 'John Doe',
            'email' => 'johndoe@example.com'
        ]);
        $expected = base64_encode(json_encode([
            'cardholderName' => 'John Doe',
            'email' => 'johndoe@example.com'
        ]));
        $this->assertEquals($expected, $request->getMInfo());
    }

    public function testMInfoWithInvalidEmail()
    {
        $this->expectException(ParameterValidationException::class);

        $request = new SaleRequest();
        $request->setMInfo([
            'cardholderName' => 'John Doe',
            'email' => 'invalid',
        ]);
    }

    public function testMInfoWithInvalidCardholderNameLength()
    {
        $this->expectException(ParameterValidationException::class);

        $request = new SaleRequest();
        $request->setMInfo([
            'cardholderName' => str_repeat('a', 46),
            'email' => 'johndoe@example.com',
        ]);
    }

    public function testMInfoWithMissingRequiredFields()
    {
        $this->expectException(ParameterValidationException::class);

        $request = new SaleRequest();
        $request->setMInfo(['email' => 'johndoe@example.com']);
    }

    public function testMInfoWithInvalidMobilePhoneStructure()
    {
        $this->expectException(ParameterValidationException::class);

        $request = new SaleRequest();
        $request->setMInfo([
            'cardholderName' => 'John Doe',
            'mobilePhone' => ['invalid' => 'invalid'],
        ]);
    }
}

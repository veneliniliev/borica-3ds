<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds\Tests;

use PHPUnit\Framework\TestCase;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Sale;

class SaleTest extends TestCase
{
    /**
     * @return void
     * @throws ParameterValidationException
     */
    public function testSignature(): void
    {
        $sale = (new Sale())
            ->setAmount(123.32)
            ->setOrder('testtt')
            ->setDescription('test')
            ->setMerchantUrl('https://test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID('VNNNNNNN')
            ->setPrivateKey(__DIR__ . '/certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20200929104707');

        $this->assertEquals(
            '9E11CB086BEE58A7903BC984E51FF1CAEFC496BEB8F231E373FD3FCE8700FFCB3946461D62B28632BBFC346F99C69FC6305BB10A11B592210E31D18CFCD173D3931570FE7049450B479DCF11D8B2CC3CC43BA53475092B838AF76C2AFECE3D55FCE0E55A85E351B78B8C7726E718DF067A926985A3F1B1C242EDC6780081455F67AFB08B038A2D556FE6D56F49C4AE6B5AF242DA521128F6283F1B1F709B0909238825E84944B589FF3028B79CC8B83048A6D5A5EE9CAE58B1E9DC3B000B8D9D5041BACB7F1E09E8A5E99AA6174AC4331BDD1BB9ACE35CB5A6057436513BEC53616910024A696FDC309FF0D031F0CCFB6330735C6C1BB516C22ABC95B579CA6D',
            $sale->generateSignature()
        );
    }
}

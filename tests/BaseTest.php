<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds\Tests;

use VenelinIliev\Borica3ds\Request;
use VenelinIliev\Borica3ds\ReversalRequest;
use VenelinIliev\Borica3ds\ReversalResponse;
use VenelinIliev\Borica3ds\SaleRequest;
use VenelinIliev\Borica3ds\SaleResponse;
use VenelinIliev\Borica3ds\StatusCheckRequest;
use VenelinIliev\Borica3ds\StatusCheckResponse;

class BaseTest extends TestCase
{
    /**
     * @return void
     */
    public function testEnvironments()
    {
        $saleData = new SaleRequest();

        //init
        $this->assertTrue($saleData->isProduction());
        $this->assertFalse($saleData->isDevelopment());

        //to dev
        $saleData->inDevelopment();
        $this->assertFalse($saleData->isProduction());
        $this->assertTrue($saleData->isDevelopment());

        $saleData->setEnvironment(false);
        $this->assertFalse($saleData->isProduction());
        $this->assertTrue($saleData->isDevelopment());

        //to prod
        $saleData->inProduction();
        $this->assertTrue($saleData->isProduction());
        $this->assertFalse($saleData->isDevelopment());

        $saleData->setEnvironment(true);
        $this->assertTrue($saleData->isProduction());
        $this->assertFalse($saleData->isDevelopment());
    }

    public function testDefaultSigningSchema()
    {
        $this->assertTrue((new SaleRequest())->getSigningSchema() === Request::SIGNING_SCHEMA_MAC_GENERAL);
        $this->assertTrue((new SaleResponse())->getSigningSchema() === Request::SIGNING_SCHEMA_MAC_GENERAL);
        $this->assertTrue((new StatusCheckRequest())->getSigningSchema() === Request::SIGNING_SCHEMA_MAC_GENERAL);
        $this->assertTrue((new StatusCheckResponse())->getSigningSchema() === Request::SIGNING_SCHEMA_MAC_GENERAL);
        $this->assertTrue((new ReversalRequest())->getSigningSchema() === Request::SIGNING_SCHEMA_MAC_GENERAL);
        $this->assertTrue((new ReversalResponse())->getSigningSchema() === Request::SIGNING_SCHEMA_MAC_GENERAL);
    }
}

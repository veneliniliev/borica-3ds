<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds\Tests;

use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\Sale;

class SaleTest extends TestCase
{
    /**
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    public function testSignature()
    {
        $sale = (new Sale())
            ->setAmount(123.32)
            ->setOrder('testtt')
            ->setDescription('test')
            ->setMerchantUrl('https://test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setPrivateKey(__DIR__ . '/certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20200929104707');

        $this->assertEquals(
            '2499BCB3CDAE8AB3274D8EBB1D3E7A43B8FD04DDA0B1FFA87C39F79B6BC52D67D42BF162BAB386BC312C49CF270C43346D59DB7D2C71C3199BC3BD5A055CB672A16D8CD8DA1B988D44C5E09D81EFE012A9E8A5B44169E264B52CC6DBBB674484242EB63FB1651ABB962D3F1D6D20672938A1C6D9D89D7CC74CD9F27D8F790EC3EF45579F0060DC6BFE7A0E4C73D9B6F5772E624FBEAD972184791C3559CC69E2EC9C6DF5C15F56C9346C77CF3FC447ECA407248CD8EF5E3863C1C49C24D56C9A7CDEDB7FCD531DAA2EF54F93277C92AB9A3095D64D6F8A18E5A92B9A9EBB184848B76BB834426801A9B189A9BD7353778E722AC1CA09A23945CD650F1744A151',
            $sale->generateSignature()
        );
    }

    /**
     * @throws ParameterValidationException
     * @throws SignatureException
     */
    public function testData()
    {
        $saleData = (new Sale())
            ->setAmount(123.32)
            ->setOrder('testtt')
            ->setDescription('test')
            ->setMerchantUrl('https://test.com')
            ->setBackRefUrl('https://test.com/back-ref-url')
            ->setTerminalID(self::TERMINAL_ID)
            ->setPrivateKey(__DIR__ . '/certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20200929104707')
            ->setAdCustBorOrderId('test text')
            ->getData();

        unset($saleData['NONCE']);

        $this->assertEquals($saleData, [
            'TRTYPE' => 1,
            'COUNTRY' => null,
            'CURRENCY' => 'BGN',
            'MERCH_GMT' => null,
            'ORDER' => 'testtt',
            'AMOUNT' => '123.32',
            'DESC' => 'test',
            'TIMESTAMP' => '20200929104707',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_URL' => 'https://test.com',
            'BACKREF' => 'https://test.com/back-ref-url',
            'AD.CUST_BOR_ORDER_ID' => 'testtttest textt',
            'ADDENDUM' => 'AD,TD',
            'P_SIGN' => '2499BCB3CDAE8AB3274D8EBB1D3E7A43B8FD04DDA0B1FFA87C39F79B6BC52D67D42BF162BAB386BC312C49CF270C43346D59DB7D2C71C3199BC3BD5A055CB672A16D8CD8DA1B988D44C5E09D81EFE012A9E8A5B44169E264B52CC6DBBB674484242EB63FB1651ABB962D3F1D6D20672938A1C6D9D89D7CC74CD9F27D8F790EC3EF45579F0060DC6BFE7A0E4C73D9B6F5772E624FBEAD972184791C3559CC69E2EC9C6DF5C15F56C9346C77CF3FC447ECA407248CD8EF5E3863C1C49C24D56C9A7CDEDB7FCD531DAA2EF54F93277C92AB9A3095D64D6F8A18E5A92B9A9EBB184848B76BB834426801A9B189A9BD7353778E722AC1CA09A23945CD650F1744A151'
        ]);
    }
}

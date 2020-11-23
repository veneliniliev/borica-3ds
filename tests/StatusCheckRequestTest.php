<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds\Tests;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\DataMissingException;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SendingException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\StatusCheckRequest;

class StatusCheckRequestTest extends TestCase
{
    /**
     * @return void
     * @throws ParameterValidationException
     * @throws SignatureException
     */
    public function testSigning()
    {
        $statusCheckRequest = (new StatusCheckRequest())
            ->setPrivateKey(__DIR__ . '/certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setPublicKey(__DIR__ . '/certificates/public.cer')
            ->setTerminalID(self::TERMINAL_ID)
            ->setOrder('114233')
            ->setOriginalTransactionType(TransactionType::SALE())
            ->setNonce('622CAAA8BF20C5A21A917DCB8401C336');

        $data = $statusCheckRequest->getData();

        $this->assertEquals(
            '5FD6E5A6A0121A599594DB1F0FC96F2CEB4CCC7B3B829E9DBA74E1DC4AF115B774A5460AAA268DB65E04B71C6E9EB6A3F7A820C27D4EA1BC648A19BC97D2577F510F4CDF4BFD6EDA4B8D2B85564791ED6287A08282027099F07166FA8416F123FEEBBC920A33A0ED5964CA02C49A7ED7D5E61F4B5D53CC14DF542BDF4221DCDA22C5864F9F722BF989CB7A2BF2ABE0B76F823561A33F2152772312429204AAB94B58C7AFC82F64D5C20069D4A5B1DF406041CAB77BCCE88C6F84704B2B33AFC82216C2F41B92129D68933CE1C59F87CEAE6B1E8CFBE6DD4CE5898F8FE6453CC7DB7519801FB05BBDE7973E18A86AFF020121B74A65EAD2741BC1D6E39DD42564',
            $data['P_SIGN']
        );
    }

    /**
     * @throws ParameterValidationException
     * @throws SignatureException
     * @throws SendingException
     * @throws DataMissingException
     */
    public function testSend()
    {
        $statusCheckRequest = (new StatusCheckRequest())
            ->inDevelopment()
            ->setPrivateKey(__DIR__ . '/certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setPublicKey(__DIR__ . '/certificates/public.cer')
            ->setTerminalID(self::TERMINAL_ID)
            ->setOrder('114233')
            ->setOriginalTransactionType(TransactionType::SALE())
            ->setNonce('622CAAA8BF20C5A21A917DCB8401C336');

        $statusCheckResponse = $statusCheckRequest->send();

        $this->assertEquals('3', $statusCheckResponse->getVerifiedData('ACTION'));
        $this->assertEquals('-24', $statusCheckResponse->getVerifiedData('RC'));
        $this->assertEquals('90', $statusCheckResponse->getVerifiedData('TRTYPE'));
        $this->assertEquals('114233', $statusCheckResponse->getVerifiedData('ORDER'));
        $this->assertEquals('622CAAA8BF20C5A21A917DCB8401C336', $statusCheckResponse->getVerifiedData('NONCE'));
    }
}

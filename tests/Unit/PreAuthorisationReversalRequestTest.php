<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */


use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;
use VenelinIliev\Borica3ds\PreAuthorisationReversalRequest;
use VenelinIliev\Borica3ds\Tests\TestCase;

class PreAuthorisationReversalRequestTest extends TestCase
{
    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testData()
    {
        $saleData = (new PreAuthorisationReversalRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setMerchantName('Some Company Ltd.')
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201013115715')
            ->setRrn('118601289138')
            ->setIntRef('CEAD95D777876F81')
            ->setNonce('FC8AC36A9FDADCB6127D273CD15DAEC3')
            ->setPublicKey(__DIR__ . '/../certificates/public.cer')
            ->setSigningSchemaMacExtended()
            ->getData();

        $this->assertEquals([
            'TRTYPE' => TransactionType::PRE_AUTHORISATION_REVERSAL,
            'CURRENCY' => 'BGN',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201013115715',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_NAME' => 'Some Company Ltd.',
            'RRN' => '118601289138',
            'INT_REF' => 'CEAD95D777876F81',
            'AD.CUST_BOR_ORDER_ID' => '145659',
            'ADDENDUM' => 'AD,TD',
            'MERCH_GMT' => date('O'),
            'NONCE' => 'FC8AC36A9FDADCB6127D273CD15DAEC3',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => 'B131F8AD95D19CA9A219F42A2F9FDE85360F0C91792663285F18AD038F7B695ED5E74D8D55C262A2FC17CC82CDC27F544E21B461C4BD0678D5E3BA742EDBA6CBCDF40CABD73C5DC236A0E27FF456A5440083241EF1ABA53B226076075A8F338E29DB60B8759ADADA5CCD068EF722368D8621B8493476C4FFFA79B82840EB6BFA21029330C37BE0EC94BACDCE84820C2694F4877F88CFD8CE44D4D900529DC57AA2BBF264E2DE1E0C1259D9CBA21B5F462C491BE32C04D90AACFD91BBB7B9A207BFA4B800A2A64CCD7FF3554D85C4F977207DE71D3398E524159E544C77F9D2118491B5A7D3861EC8C1532DB6F03758E9140790B2D1AE101580450C644CE4D9CB'
        ], $saleData);
    }

    /**
     * @throws ParameterValidationException|SignatureException
     */
    public function testDataMacGeneral()
    {
        $data = (new PreAuthorisationReversalRequest())
            ->setAmount(1)
            ->setCurrency('BGN')
            ->setOrder(145659)
            ->setDescription('Детайли плащане.')
            ->setTerminalID(self::TERMINAL_ID)
            ->setMerchantId(self::MERCHANT_ID)
            ->setMerchantName('Some Company Ltd.')
            ->setPrivateKey(__DIR__ . '/../certificates/test.key')
            ->setPrivateKeyPassword('test')
            ->setSignatureTimestamp('20201014095541')
            ->setRrn('028701253242')
            ->setIntRef('B7A68A9F37E8586E')
            ->setNonce('7D51498A3C22B86DD57EFB699A175714')
            ->setPublicKey(__DIR__ . '/../certificates/public.cer')
            ->setSigningSchemaMacGeneral()
            ->getData();

        $this->assertEquals([
            'TRTYPE' => TransactionType::PRE_AUTHORISATION_REVERSAL,
            'CURRENCY' => 'BGN',
            'ORDER' => '145659',
            'AMOUNT' => '1.00',
            'DESC' => 'Детайли плащане.',
            'TIMESTAMP' => '20201014095541',
            'TERMINAL' => self::TERMINAL_ID,
            'MERCH_NAME' => 'Some Company Ltd.',
            'RRN' => '028701253242',
            'INT_REF' => 'B7A68A9F37E8586E',
            'AD.CUST_BOR_ORDER_ID' => '145659',
            'ADDENDUM' => 'AD,TD',
            'MERCH_GMT' => date('O'),
            'NONCE' => '7D51498A3C22B86DD57EFB699A175714',
            'MERCHANT' => self::MERCHANT_ID,
            'P_SIGN' => '0EDC2FD48A4E2AC99F50A13134C8D5CDB88118994C70FB0C1BE184B1F58AD2383168454907F85ACEB7A84800D839E637B6A458F10670D8BDCAFEC36EA8320C80E4096121CFDB2D4D2A11DC91E6E2DEA146AF9DBDB3D9FB5CF926F4CE1DC7E531980FDD6EEFB2F78C929E9E0E8597AC8526F6FE1ADA0ED02391D18481B8F85C03F8DC01AA9A3EA484C091853C86F7BD031CB4582BE36AB8EDD330EC0B0A89DA593ADABFF3BF8DFEC460F21D649434752ADB9C43CCB7116962A798E049BDF39313F063EE8E1B3DC50DF8DEB5C5982B30B792948077799ED3D9EF1C242A17FB1A1651E34B6FAFAEF7EC048B321F4D56F4E8BC7E6B8E4FF36D6A16EC841A9E8F7E35'
        ], $data);
    }
}

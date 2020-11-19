<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Exceptions\DataMissingException;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;

/**
 * Class Sale
 * @package VenelinIliev\Borica3ds
 */
class SaleResponse extends Response
{
    /**
     * @var array
     */
    private $responseData;

    /**
     * @var boolean
     */
    private $dataIsVerified = false;

    /**
     * Is success payment?
     * @return boolean
     * @throws DataMissingException
     * @throws ParameterValidationException
     * @throws Exceptions\SignatureException
     */
    public function isSuccessful()
    {
        return $this->getResponseCode() === '00';
    }

    /**
     * Get response code - value of 'RC' field
     * @return string
     * @throws Exceptions\SignatureException|ParameterValidationException|DataMissingException
     */
    public function getResponseCode()
    {
        return $this->getVerifiedData('RC');
    }

    /**
     * Get verified data by key
     * @param string $key Data key.
     * @return mixed
     * @throws Exceptions\SignatureException|ParameterValidationException|DataMissingException
     */
    protected function getVerifiedData($key)
    {
        if (!$this->dataIsVerified) {
            $this->verifyData();
        }

        $data = $this->getResponseData();
        if (!isset($data[$key])) {
            throw new DataMissingException($key . ' missing in verified response data');
        }

        return $data[$key];
    }

    /**
     * Verify data with public certificate
     * @return void
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    private function verifyData()
    {
        if ($this->dataIsVerified) {
            return;
        }

        $responseData = $this->getResponseData();

        $verifyingFields = [
            'ACTION',
            'RC',
            'APPROVAL',
            'TERMINAL',
            'TRTYPE',
            'AMOUNT',
            'CURRENCY',
            'ORDER',
            'RRN',
            'INT_REF',
            'PARES_STATUS',
            'ECI',
            'TIMESTAMP',
            'NONCE',
        ];

        $dateToVerify = [];

        /*
         * Check required data
         */
        foreach (array_merge($verifyingFields, ['P_SIGN']) as $key) {
            if (!array_key_exists($key, $responseData)) {
                throw new ParameterValidationException($key . ' is missing in response data!');
            }
            if ($key != 'P_SIGN') {
                $dateToVerify[] = $responseData[$key];
            }
        }

        $this->verifyPublicSignature($dateToVerify, $responseData['P_SIGN']);

        $this->dataIsVerified = true;
    }

    /**
     * Get response data
     * @note If response data is not set - set data to $_POST
     * @return array
     */
    public function getResponseData()
    {
        if (empty($this->responseData)) {
            $this->setResponseData($_POST);
        }
        return $this->responseData;
    }

    /**
     * Set response data
     * @param array $responseData Response data from borica.
     * @return SaleResponse
     */
    public function setResponseData(array $responseData)
    {
        $this->dataIsVerified = false;
        $this->responseData = $responseData;
        return $this;
    }
}

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
     * @return boolean
     * @throws Exceptions\SignatureException|ParameterValidationException|DataMissingException
     */
    public function isSuccess()
    {
        return $this->getResponseCode() === '00';
    }

    /**
     * @return string
     * @throws Exceptions\SignatureException|ParameterValidationException|DataMissingException
     */
    public function getResponseCode()
    {
        return $this->getVerifiedData('RC');
    }

    /**
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
     * @return void
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    private function verifyData()
    {
        if ($this->dataIsVerified) {
            return;
        }

        $responseData = $this->getResponseData();

        /*
         * Check required data
         */
        foreach (['TERMINAL', 'TRTYPE', 'AMOUNT', 'TIMESTAMP', 'P_SIGN', 'RC'] as $key) {
            if (!array_key_exists($key, $responseData)) {
                throw new ParameterValidationException($key . ' is missing in response data!');
            }
        }

        $this->verifyPublicSignature([
            $responseData['TERMINAL'],
            $responseData['TRTYPE'],
            $responseData['AMOUNT'],
            $responseData['TIMESTAMP'],
        ], $responseData['P_SIGN']);

        $this->dataIsVerified = true;
    }

    /**
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

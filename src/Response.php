<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Exceptions\DataMissingException;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;

abstract class Response extends Base
{
    /**
     * @var boolean
     */
    private $dataIsVerified = false;

    /**
     * @var array
     */
    private $responseData;

    /**
     * Get verified data by key
     *
     * @param string $key Data key.
     *
     * @return mixed
     * @throws Exceptions\SignatureException|ParameterValidationException|DataMissingException
     */
    public function getVerifiedData($key)
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
     *
     * @return void
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    protected function verifyData()
    {
        if ($this->dataIsVerified) {
            return;
        }

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

        $dataToVerify = [];

        /**
         * Response from borica
         */
        $responseFromBorica = $this->getResponseData(false);

        /*
         * Check required data
         */
        foreach (array_merge($verifyingFields, ['P_SIGN']) as $key) {
            if (!array_key_exists($key, $responseFromBorica)) {
                throw new ParameterValidationException($key . ' is missing in response data!');
            }
            if ($key != 'P_SIGN') {

                /**
                 * това нямам идея защо така са го направили... но да :/
                 * @see 5.2 от документацията
                 * @note прави се само за TRTYPE = 90!
                 */
                if ($key == 'CURRENCY' && empty($responseFromBorica[$key]) && $responseFromBorica['TRTYPE'] == 90) {
                    $responseFromBorica['CURRENCY'] = 'USD';
                }

                $dataToVerify[] = $responseFromBorica[$key];
            }
        }

        $this->verifyPublicSignature($dataToVerify, $responseFromBorica['P_SIGN']);

        $this->dataIsVerified = true;
    }

    /**
     * Verify data with public certificate
     *
     * @param array  $data            Данни върху които да генерира подписа.
     * @param string $publicSignature Публичен подпис.
     *
     * @return void
     * @throws ParameterValidationException|SignatureException
     */
    protected function verifyPublicSignature(array $data, $publicSignature)
    {
        /*
         * generate signature
         */
        $signature = $this->getSignatureSource($data, true);

        /*
         * Open certificate file
         */
        $fp = fopen($this->getPublicKey(), "r");
        $publicKeyContent = fread($fp, filesize($this->getPublicKey()));
        fclose($fp);

        /*
         * get public key
         */
        $publicKey = openssl_get_publickey($publicKeyContent);
        if (!$publicKey) {
            throw new SignatureException(openssl_error_string());
        }

        /*
         * verifying
         */
        $verifyStatus = openssl_verify($signature, hex2bin($publicSignature), $publicKey, OPENSSL_ALGO_SHA256);
        if ($verifyStatus !== 1) {
            throw new SignatureException(openssl_error_string());
        }

        if (PHP_MAJOR_VERSION < 8) {
            /**
             * @deprecated in PHP 8.0
             * @note       The openssl_pkey_free() function is deprecated and no longer has an effect,
             * instead the OpenSSLAsymmetricKey instance is automatically destroyed if it is no
             * longer referenced.
             * @see        https://github.com/php/php-src/blob/master/UPGRADING#L397
             */
            openssl_pkey_free($publicKey);
        }
    }

    /**
     * Get response data
     *
     * @note If response data is not set - set data to $_POST
     *
     * @param boolean $verify Verify data before return.
     *
     * @return array
     * @throws ParameterValidationException
     * @throws SignatureException
     */
    public function getResponseData($verify = true)
    {
        if (empty($this->responseData)) {
            $this->setResponseData($_POST);
        }

        if ($verify) {
            $this->verifyData();
        }

        return $this->responseData;
    }

    /**
     * Set response data
     *
     * @param array $responseData Response data from borica.
     *
     * @return Response
     */
    public function setResponseData(array $responseData)
    {
        $this->dataIsVerified = false;
        $this->responseData = $responseData;
        return $this;
    }
}

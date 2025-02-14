<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\Action;
use VenelinIliev\Borica3ds\Enums\ResponseCode;
use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\DataMissingException;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;

abstract class Response extends Base
{
    /**
     * @var boolean
     */
    protected $dataIsVerified = false;

    /**
     * @var array
     */
    protected $responseData;

    /**
     * Determine the repose class.
     *
     * Return the correct response class instance based on the response data. If response data is not
     * provided it will use the data from $_POST
     *
     * @param array $responseData Response data from Borica.
     * @return Response
     * @throws DataMissingException
     */
    public static function determineResponse(array $responseData = null)
    {
        if (is_null($responseData)) {
            $responseData = $_POST;
        }

        if (empty($responseData['TRTYPE'])) {
            throw new DataMissingException('TRTYPE missing or empty in response data');
        }

        switch ($responseData['TRTYPE']) {
            case TransactionType::SALE:
                $response = new SaleResponse();
                break;
            case TransactionType::PRE_AUTHORISATION:
                $response = new PreAuthorisationResponse();
                break;
            case TransactionType::PRE_AUTHORISATION_COMPLETION:
                $response = new PreAuthorisationCompletionResponse();
                break;
            case TransactionType::PRE_AUTHORISATION_REVERSAL:
                $response = new PreAuthorisationReversalResponse();
                break;
            case TransactionType::REVERSAL:
                $response = new ReversalResponse();
                break;
            case TransactionType::TRANSACTION_STATUS_CHECK:
                $response = new StatusCheckResponse();
                break;
            default:
                throw new DataMissingException('Unknown transaction type');
        }

        return $response->setResponseData($responseData);
    }

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

        $verifyingFields = $this->getVerifyingFields();

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
                 *
                 * @see  5.2 от документацията
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
     * @return string[]
     */
    protected function getVerifyingFields()
    {
        return [
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
            throw new SignatureException('Open public key error: '.openssl_error_string());
        }

        /*
         * verifying
         */
        $verifyStatus = openssl_verify($signature, hex2bin($publicSignature), $publicKey, OPENSSL_ALGO_SHA256);
        if ($verifyStatus !== 1) {
            throw new SignatureException('Data signature verify error! Error: ' . openssl_error_string());
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
     * Is corresponding request was successful?
     *
     * @return boolean
     * @throws DataMissingException
     * @throws ParameterValidationException
     * @throws Exceptions\SignatureException
     */
    public function isSuccessful()
    {
        return $this->getResponseCode() === ResponseCode::SUCCESS &&
            $this->getAction() === Action::SUCCESS;
    }

    /**
     * Get response code - value of 'RC' field
     *
     * @return string
     * @throws Exceptions\SignatureException|ParameterValidationException|DataMissingException
     */
    public function getResponseCode()
    {
        return $this->getVerifiedData('RC');
    }

    /**
     * Get action - value of 'ACTION' field
     *
     * @return string
     * @throws Exceptions\SignatureException|ParameterValidationException|DataMissingException
     */
    public function getAction()
    {
        return $this->getVerifiedData('ACTION');
    }
}

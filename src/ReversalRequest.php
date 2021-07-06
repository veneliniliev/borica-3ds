<?php
/*
 * Copyright (c) 2021. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SendingException;

class ReversalRequest extends Request implements RequestInterface
{
    /**
     * @var string
     */
    protected $rrn;

    /**
     * @var string
     */
    protected $intRef;

    /**
     * @var string
     */
    protected $merchantName;

    /**
     * StatusCheckRequest constructor.
     */
    public function __construct()
    {
        $this->setTransactionType(TransactionType::REVERSAL());
    }

    /**
     * Send data to borica
     *
     * @return ReversalResponse
     * @throws Exceptions\SignatureException|ParameterValidationException|SendingException
     */
    public function send()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->getEnvironmentUrl());
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->getData()));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        if (curl_error($ch)) {
            throw new SendingException(curl_error($ch));
        }
        curl_close($ch);

        return (new ReversalResponse())
            ->setResponseData(json_decode($response, true))
            ->setPublicKey($this->getPublicKey());
    }

    /**
     * @return array
     * @throws Exceptions\SignatureException
     * @throws ParameterValidationException
     */
    public function getData()
    {
        return [
            'TERMINAL' => $this->getTerminalID(),
            'TRTYPE' => $this->getTransactionType()->getValue(),
            'AMOUNT' => $this->getAmount(),
            'CURRENCY' => $this->getCurrency(),
            'ORDER' => $this->getOrder(),
            'DESC' => $this->getDescription(),
            'MERCHANT' => $this->getMerchantId(),
            'MERCH_NAME' => $this->getMerchantName(),
            'RRN' => $this->getRrn(),
            'INT_REF' => $this->getIntRef(),
            'TIMESTAMP' => $this->getSignatureTimestamp(),
            'NONCE' => $this->getNonce(),
            'P_SIGN' => $this->generateSignature(),
        ];
    }

    /**
     * @return string
     */
    public function getRrn()
    {
        return $this->rrn;
    }

    /**
     * @return string
     */
    public function getIntRef()
    {
        return $this->intRef;
    }

    /**
     * @return string
     * @throws Exceptions\SignatureException
     * @throws ParameterValidationException
     */
    public function generateSignature()
    {
        $this->validateRequiredParameters();
        return $this->getPrivateSignature([
            $this->getTerminalID(),
            $this->getTransactionType()->getValue(),
            $this->getAmount(),
            $this->getCurrency(),
            $this->getOrder(),
            $this->getMerchantId(),
            $this->getSignatureTimestamp(),
            $this->getNonce()
        ]);
    }

    /**
     * @return void
     * @throws ParameterValidationException
     */
    public function validateRequiredParameters()
    {
        if (empty($this->getTransactionType())) {
            throw new ParameterValidationException('Transaction type is empty!');
        }

        if (empty($this->getOrder())) {
            throw new ParameterValidationException('Order is empty!');
        }

        if (empty($this->getPublicKey())) {
            throw new ParameterValidationException('Please set public key for validation response!');
        }

        if (empty($this->getTerminalID())) {
            throw new ParameterValidationException('TerminalID is empty!');
        }

        if (empty($this->getIntRef())) {
            throw new ParameterValidationException('Internal reference is empty!');
        }

        if (empty($this->getRrn())) {
            throw new ParameterValidationException('Payment reference is empty!');
        }
    }

    /**
     * @return array
     * @throws Exceptions\SignatureException
     * @throws ParameterValidationException
     */
    public function generateForm()
    {
        return $this->getData();
    }

    /**
     * Set transaction reference.
     *
     * @param string $rrn Референция на транзакцията.
     *
     * @return ReversalRequest
     */
    public function setRrn($rrn)
    {
        $this->rrn = $rrn;
        return $this;
    }

    /**
     * Set transaction internal reference.
     *
     * @param string $intRef Вътрешна референция на транзакцията.
     *
     * @return ReversalRequest
     */
    public function setIntRef($intRef)
    {
        $this->intRef = $intRef;
        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantName()
    {
        return $this->merchantName;
    }

    /**
     * @param string $merchantName Merchant name.
     *
     * @return ReversalRequest
     */
    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
        return $this;
    }
}

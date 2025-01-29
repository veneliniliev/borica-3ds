<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SendingException;

class ReversalRequest extends Request implements RequestInterface
{
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
     * @throws Exceptions\SignatureException
     * @throws ParameterValidationException
     */
    public function generateSignature()
    {
        $this->validateRequiredParameters();
        if (!$this->isSigningSchemaMacGeneral()) {
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

        return parent::generateSignature();
    }

    /**
     * @return void
     * @throws ParameterValidationException
     */
    public function validateRequiredParameters()
    {
        if (empty($this->getPublicKey())) {
            throw new ParameterValidationException('Please set public key for validation response!');
        }

        if (empty($this->getIntRef())) {
            throw new ParameterValidationException('Internal reference is empty!');
        }

        if (empty($this->getRrn())) {
            throw new ParameterValidationException('Payment reference is empty!');
        }

        parent::validateRequiredParameters();
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
}

<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SendingException;
use VenelinIliev\Borica3ds\RequestTypes\Direct;

class PreAuthorisationReversalRequest extends Request implements RequestInterface
{
    /**
     * PreAuthorisationReversalRequest constructor.
     */
    public function __construct()
    {
        $this->setTransactionType(TransactionType::PRE_AUTHORISATION_REVERSAL());
        $this->requestType = new Direct();
    }

    /**
     * Send data to borica
     *
     * @return PreAuthorisationReversalResponse
     * @throws Exceptions\SignatureException|ParameterValidationException|SendingException
     */
    public function send()
    {
        $response = parent::send();

        return (new PreAuthorisationReversalResponse())
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
        return array_filter([
            'TERMINAL' => $this->getTerminalID(),
            'TRTYPE' => $this->getTransactionType()->getValue(),
            'AMOUNT' => $this->getAmount(),
            'CURRENCY' => $this->getCurrency(),
            'ORDER' => $this->getOrder(),
            'DESC' => $this->getDescription(),
            'MERCHANT' => $this->getMerchantId(),
            'MERCH_NAME' => $this->getMerchantName(),
            'MERCH_URL' => $this->getMerchantUrl(),
            'EMAIL' => $this->getEmailAddress(),
            'COUNTRY' => $this->getCountryCode(),
            'MERCH_GMT' => $this->getMerchantGMT(),
            'LANG' => $this->getLang(),
            'RRN' => $this->getRrn(),
            'INT_REF' => $this->getIntRef(),
            'TIMESTAMP' => $this->getSignatureTimestamp(),
            'NONCE' => $this->getNonce(),
            'P_SIGN' => $this->generateSignature(),
        ]) + $this->generateAdCustBorOrderId();
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
}

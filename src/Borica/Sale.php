<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;

/**
 * Class Sale
 * @package VenelinIliev\Borica3ds
 */
class Sale extends Request implements RequestInterface
{

    /**
     * @var string
     */
    protected $merchantUrl;

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $emailAddress;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var integer
     */
    protected $merchantGMT;

    /**
     * @var string
     */
    protected $adCustBorOrderId;

    /**
     * Sale constructor.
     */
    public function __construct()
    {
        $this->setTransactionType(TransactionType::SALE());
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @param mixed $merchantId
     * @return Sale
     * @throws ParameterValidationException
     */
    public function setMerchantId($merchantId)
    {
        if (mb_strlen($merchantId) != 15) {
            throw new ParameterValidationException('Merchant ID must be exact 15 characters');
        }
        $this->merchantId = $merchantId;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     * @return Sale
     * @throws ParameterValidationException
     */
    public function setEmailAddress($emailAddress)
    {
        if (mb_strlen($emailAddress) > 80) {
            throw new ParameterValidationException('Email address for notifications must be maximum 80 characters');
        }
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return void
     * @throws ParameterValidationException
     */
    public function send()
    {
        $this->validateRequiredParameters();

        $response = $this->getGuzzleClient()
            ->post(
                $this->getEnvironmentUrl(),
                [
                    'debug' => !$this->isProduction(),
                    'form_params' => $this->getData(),
                    'allow_redirects' => true
                ]
            );

        die(var_dump($response));
    }

    /**
     * Validate required fields to post
     * @return void
     * @throws ParameterValidationException
     */
    public function validateRequiredParameters()
    {
        if (empty($this->getTransactionType())) {
            throw new ParameterValidationException('Transaction type is empty!');
        }

        if (empty($this->getAmount())) {
            throw new ParameterValidationException('Amount is empty!');
        }

        if (empty($this->getCurrency())) {
            throw new ParameterValidationException('Currency is empty!');
        }

        if (empty($this->getOrder())) {
            throw new ParameterValidationException('Order is empty!');
        }

        if (empty($this->getDescription())) {
            throw new ParameterValidationException('Description is empty!');
        }

        if (empty($this->getMerchantUrl())) {
            throw new ParameterValidationException('Merchant url is empty!');
        }

        if (empty($this->getBackRefUrl())) {
            throw new ParameterValidationException('Back ref url is empty!');
        }

        if (empty($this->getTerminalID())) {
            throw new ParameterValidationException('TerminalID is empty!');
        }
    }

    /**
     * @return string
     */
    public function getMerchantUrl()
    {
        return $this->merchantUrl;
    }

    /**
     * @param string $merchantUrl
     * @return Sale
     * @throws ParameterValidationException
     */
    public function setMerchantUrl($merchantUrl)
    {
        if (mb_strlen($merchantUrl) > 250) {
            throw new ParameterValidationException('Merchant URL must be maximum 250 characters');
        }

        $this->merchantUrl = $merchantUrl;
        return $this;
    }

    /**
     * @return array
     * @throws Exceptions\SignatureException
     */
    public function getData()
    {
        return [
                'NONCE' => strtoupper(bin2hex(openssl_random_pseudo_bytes(16))),
                'P_SIGN' => $this->generateSignature(),

                'TRTYPE' => $this->getTransactionType()->getValue(),
                'COUNTRY' => $this->getCountryCode(),
                'CURRENCY' => $this->getCurrency(),

                'MERCH_GMT' => $this->getMerchantGMT(),

                'ORDER' => $this->getOrder(),
                'AMOUNT' => $this->getAmount(),
                'DESC' => $this->getDescription(),
                'TIMESTAMP' => $this->getSignatureTimestamp(),

                'TERMINAL' => $this->getTerminalID(),
                'MERCH_URL' => $this->getMerchantUrl(),
                'BACKREF' => $this->getBackRefUrl(),
            ] + $this->generateAdCustBorOrderId();
    }

    /**
     * @return string
     * @throws Exceptions\SignatureException
     */
    public function generateSignature()
    {
        return $this->getSignature([
            $this->getTerminalID(),
            $this->getTransactionType()->getValue(),
            $this->getAmount(),
            $this->getCurrency(),
            $this->getSignatureTimestamp()
        ]);
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return Sale
     * @throws ParameterValidationException
     */
    public function setCountryCode($countryCode)
    {
        if (mb_strlen($countryCode) != 2) {
            throw new ParameterValidationException('Country code must be exact 2 characters (ISO2)');
        }
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return integer|null
     */
    public function getMerchantGMT()
    {
        return $this->merchantGMT;
    }

    /**
     * @param integer $merchantGMT
     * @return Sale
     */
    public function setMerchantGMT($merchantGMT)
    {
        $this->merchantGMT = $merchantGMT;
        return $this;
    }

    /**
     * @return array
     */
    private function generateAdCustBorOrderId()
    {
        $orderString = $this->getOrder();

        if (!empty($this->getAdCustBorOrderId())) {
            $orderString .= $this->getAdCustBorOrderId();
        }

        /*
         * полето не трябва да съдържа символ “;”
         */
        $orderString .= str_ireplace(';', '', $orderString);

        return [
            'AD.CUST_BOR_ORDER_ID' => mb_substr($orderString, 0, 16),
            'ADDENDUM' => 'AD,TD',
        ];
    }

    /**
     * @return string
     */
    public function getAdCustBorOrderId()
    {
        return $this->adCustBorOrderId;
    }

    /**
     * @param string $adCustBorOrderId
     * @return Sale
     */
    public function setAdCustBorOrderId($adCustBorOrderId)
    {
        $this->adCustBorOrderId = $adCustBorOrderId;
        return $this;
    }
}

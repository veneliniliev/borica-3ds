<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;

/**
 * Class Sale
 *
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
    protected $merchantName;

    /**
     * @var string
     */
    protected $emailAddress;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $merchantGMT;

    /**
     * @var string
     */
    protected $adCustBorOrderId;

    /**
     * @var string
     */
    protected $nonce;

    /**
     * Sale constructor.
     */
    public function __construct()
    {
        $this->setTransactionType(TransactionType::SALE());
    }

    /**
     * Get notification email address
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set notification email address
     *
     * @param string $emailAddress E-mail адрес за уведомления.
     *
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
     * Send to borica. Generate form and auto submit with JS.
     *
     * @return void
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    public function send()
    {
        $html = $this->generateForm();

        $html .= '<script>
            document.getElementById("borica3dsRedirectForm").submit()
        </script>';

        die($html);
    }

    /**
     * Generate HTML hidden form
     *
     * @return string
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    public function generateForm()
    {
        $html = '<form 
	        action="' . $this->getEnvironmentUrl() . '" 
	        style="display: none;" 
	        method="POST" 
	        id="borica3dsRedirectForm"
        >';

        $inputs = $this->getData();
        foreach ($inputs as $key => $value) {
            $html .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }

        $html .= '</form>';

        return $html;
    }

    /**
     * Get data required for request to borica
     *
     * @return array
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    public function getData()
    {
        return [
                'NONCE' => $this->getNonce(),
                'P_SIGN' => $this->generateSignature(),

                'TRTYPE' => $this->getTransactionType()->getValue(),
                'COUNTRY' => $this->getCountryCode(),
                'CURRENCY' => $this->getCurrency(),

                'MERCH_GMT' => $this->getMerchantGMT(),
                'MERCHANT' => $this->getMerchantId(),
                'MERCH_NAME' => $this->getMerchantName(),
                'MERCH_URL' => $this->getMerchantUrl(),

                'ORDER' => $this->getOrder(),
                'AMOUNT' => $this->getAmount(),
                'DESC' => $this->getDescription(),
                'TIMESTAMP' => $this->getSignatureTimestamp(),

                'TERMINAL' => $this->getTerminalID(),
                'BACKREF' => $this->getBackRefUrl(),
            ] + $this->generateAdCustBorOrderId();
    }

    /**
     * @return string
     */
    private function getNonce()
    {
        if (!empty($this->nonce)) {
            return $this->nonce;
        }
        $this->setNonce(strtoupper(bin2hex(openssl_random_pseudo_bytes(16))));
        return $this->nonce;
    }

    /**
     * @param string $nonce
     * @return Sale
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * Generate signature of data
     *
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
     * Validate required fields to post
     *
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

        if (empty($this->getBackRefUrl()) && $this->isDevelopment()) {
            throw new ParameterValidationException('Back ref url is empty! (required in development)');
        }

        if (empty($this->getMerchantId())) {
            throw new ParameterValidationException('Merchant ID is empty!');
        }

        if (empty($this->getTerminalID())) {
            throw new ParameterValidationException('TerminalID is empty!');
        }
    }

    /**
     * Get country code
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set country code
     *
     * @param string $countryCode Двубуквен код на държавата, където се намира магазинът на търговеца.
     *
     * @return Sale
     * @throws ParameterValidationException
     */
    public function setCountryCode($countryCode)
    {
        if (mb_strlen($countryCode) != 2) {
            throw new ParameterValidationException('Country code must be exact 2 characters (ISO2)');
        }
        $this->countryCode = strtoupper($countryCode);
        return $this;
    }

    /**
     * Get merchant GMT
     *
     * @return string|null
     */
    public function getMerchantGMT()
    {
        if (empty($this->merchantGMT)) {
            $this->setMerchantGMT(date('O'));
        }
        return $this->merchantGMT;
    }

    /**
     * Set merchant GMT
     *
     * @param string $merchantGMT Отстояние на часовата зона на търговеца от UTC/GMT  (напр. +03).
     *
     * @return Sale
     */
    public function setMerchantGMT($merchantGMT)
    {
        $this->merchantGMT = $merchantGMT;
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
     * @param string $merchantName
     *
     * @return Sale
     */
    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
        return $this;
    }

    /**
     * Get merchant URL
     *
     * @return string
     */
    public function getMerchantUrl()
    {
        return $this->merchantUrl;
    }

    /**
     * Set merchant URL
     *
     * @param string $merchantUrl URL на web сайта на търговеца.
     *
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
     * Generate AD.CUST_BOR_ORDER_ID borica field
     *
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
     * Get 'AD.CUST_BOR_ORDER_ID' field
     *
     * @return string
     */
    public function getAdCustBorOrderId()
    {
        return $this->adCustBorOrderId;
    }

    /**
     * Set 'AD.CUST_BOR_ORDER_ID' field
     *
     * @param string $adCustBorOrderId Идентификатор на поръчката за Банката на търговеца във финансовите файлове.
     *
     * @return Sale
     */
    public function setAdCustBorOrderId($adCustBorOrderId)
    {
        $this->adCustBorOrderId = $adCustBorOrderId;
        return $this;
    }
}

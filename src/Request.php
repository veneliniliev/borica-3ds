<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\Language;
use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SendingException;
use VenelinIliev\Borica3ds\RequestTypes\RequestType;

/**
 * Borica request
 */
abstract class Request extends Base
{
    /**
     * @var mixed
     */
    protected $signatureTimestamp;

    /**
     * @var string
     */
    protected $backRefUrl;

    /**
     * @var string|float|integer
     */
    protected $amount = null;

    /**
     * @var string
     */
    protected $currency = 'BGN';

    /**
     * @var string
     */
    protected $description;

    /**
     * @var TransactionType
     */
    protected $transactionType;

    /**
     * @var mixed
     */
    protected $order;

    /**
     * @var string
     */
    protected $nonce;

    /**
     * @var array
     */
    protected $mInfo;

    /**
     * Transaction language.
     *
     * @var null|string
     */
    protected $lang = null;

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
    protected $rrn;

    /**
     * @var string
     */
    protected $intRef;

    /**
     * @var RequestType
     */
    protected $requestType;

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param  string $description Описание на поръчката.
     *
     * @return Request
     * @throws ParameterValidationException
     */
    public function setDescription($description)
    {
        if (mb_strlen($description) > 50) {
            throw new ParameterValidationException('Description must be max 50 digits');
        }
        $this->description = $description;
        return $this;
    }

    /**
     * Get back ref url
     *
     * @deprecated This is missing from the Borica documentation v5.0 / 29.05.2024, and it is not working anymore.
     * @return string
     */
    public function getBackRefUrl()
    {
        return $this->backRefUrl;
    }

    /**
     * Set back ref url
     *
     * @deprecated This is missing from the Borica documentation v5.0 / 29.05.2024, and it is not working anymore.
     * @param  string $backRefUrl URL на търговеца за изпращане на резултата от авторизацията.
     * @return Request
     * @throws ParameterValidationException
     */
    public function setBackRefUrl($backRefUrl)
    {
        if (!filter_var($backRefUrl, FILTER_VALIDATE_URL)) {
            throw new ParameterValidationException('Backref url is not valid!');
        }

        $this->backRefUrl = $backRefUrl;
        return $this;
    }

    /**
     * Get order
     *
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set order
     *
     * @param  string|integer $order Номер на поръчката за търговеца, 6 цифри, който трябва да бъде уникален за деня.
     *
     * @return Request
     * @throws ParameterValidationException
     */
    public function setOrder($order)
    {
        if (mb_strlen($order) > 6) {
            throw new ParameterValidationException('Order must be max 6 digits');
        }

        $this->order = str_pad($order, 6, "0", STR_PAD_LEFT);
        return $this;
    }

    /**
     * Get transaction type
     *
     * @return TransactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set transaction type
     *
     * @param  TransactionType $transactionType Тип на транзакцията.
     *
     * @return Request
     */
    public function setTransactionType(TransactionType $transactionType)
    {
        $this->transactionType = $transactionType;
        return $this;
    }

    /**
     * Get amount
     *
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amount
     *
     * @param  string|float|integer $amount Обща стойност на поръчката по стандарт ISO_4217 с десетичен разделител точка.
     *
     * @return Request
     */
    public function setAmount($amount)
    {
        $this->amount = number_format($amount, 2, '.', '');
        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currency
     *
     * @param  string $currency Валута на поръчката: три буквен код на валута по стандарт ISO 4217.
     *
     * @return Request
     * @throws ParameterValidationException
     */
    public function setCurrency($currency)
    {
        if (mb_strlen($currency) != 3) {
            throw new ParameterValidationException('3 character currency code');
        }
        $this->currency = mb_strtoupper($currency);
        return $this;
    }

    /**
     * Get signature timestamp
     *
     * @return string
     */
    public function getSignatureTimestamp()
    {
        if (empty($this->signatureTimestamp)) {
            $this->setSignatureTimestamp();
        }

        return $this->signatureTimestamp;
    }

    /**
     * Set signature timestamp
     *
     * @param  string|null $signatureTimestamp Дата на подпис/изпращане на данните.
     *
     * @return Request
     */
    public function setSignatureTimestamp($signatureTimestamp = null)
    {
        if (empty($signatureTimestamp)) {
            $this->signatureTimestamp = gmdate('YmdHis');
            return $this;
        }

        $this->signatureTimestamp = $signatureTimestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getNonce()
    {
        if (!empty($this->nonce)) {
            return $this->nonce;
        }
        $this->setNonce(strtoupper(bin2hex(openssl_random_pseudo_bytes(16))));
        return $this->nonce;
    }

    /**
     * @param  string $nonce Nonce.
     *
     * @return Request
     */
    public function setNonce($nonce)
    {
        $this->nonce = $nonce;
        return $this;
    }

    /**
     * @return string
     */
    public function getMInfo()
    {
        if (!empty($this->mInfo)) {
            return base64_encode(json_encode($this->mInfo));
        }
        return '';
    }

    /**
     * @param  array $mInfo
     *
     * @return Request
     * @throws ParameterValidationException
     */
    public function setMInfo($mInfo)
    {
        // Check for required fields (cardholderName and email or mobilePhone)
        if (!isset($mInfo['cardholderName']) ||
            (!isset($mInfo['email']) && !isset($mInfo['mobilePhone']))) {
            throw new ParameterValidationException('CardholderName and email or MobilePhone must be provided');
        }

        // Check the maximum length of cardholderName
        if (strlen($mInfo['cardholderName']) > 45) {
            throw new ParameterValidationException('CardHolderName must be at most 45 characters');
        }

        // Check for a valid email address format
        if (isset($mInfo['email']) && !filter_var($mInfo['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ParameterValidationException('Email must be a valid email address');
        }

        // Check the structure for the mobile phone
        if (isset($mInfo['mobilePhone'])) {
            if (!isset($mInfo['mobilePhone']['cc']) || !isset($mInfo['mobilePhone']['subscriber'])) {
                throw new ParameterValidationException('MobilePhone must contain both cc and subscriber');
            }
        }

        $this->mInfo = $mInfo;
        return $this;
    }

    /**
     * Get the language of the transaction.
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set the language of the transaction.
     *
     * The selected language of the form where the user will enter the payment info.
     * Supported languages are listed in VenelinIliev\Borica3ds\Enums\Language.
     *
     * @param  string|null $lang Two letter language code.
     * @return Request
     * @throws ParameterValidationException
     */
    public function setLang($lang)
    {
        if (empty($lang)) {
            $this->lang = null;
        } else {
            if (mb_strlen($lang) != 2) {
                throw new ParameterValidationException('2 character language code');
            }
            $lang = mb_strtoupper($lang);
            if (!Language::isValid($lang)) {
                throw new ParameterValidationException('Not a valid language code');
            }
            $this->lang = $lang;
        }
        return $this;
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
     * @return Request
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
     * @return Request
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
     * @param string $merchantName Merchant name.
     *
     * @return Request
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
     * @return Request
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
     * @return Request
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
     * Generate AD.CUST_BOR_ORDER_ID borica field
     *
     * @return array
     */
    protected function generateAdCustBorOrderId()
    {
        $orderString = $this->getAdCustBorOrderId();

        if (empty($orderString)) {
            $orderString = $this->getOrder();
        }

        /*
         * полето не трябва да съдържа символ “;”
         */
        $orderString = str_ireplace(';', '', $orderString);

        return [
            'AD.CUST_BOR_ORDER_ID' => mb_substr($orderString, 0, 22),
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
     * @return Request
     */
    public function setAdCustBorOrderId($adCustBorOrderId)
    {
        $this->adCustBorOrderId = $adCustBorOrderId;
        return $this;
    }

    /**
     * Set transaction reference.
     *
     * @param string $rrn Референция на транзакцията.
     *
     * @return Request
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
     * @return Request
     */
    public function setIntRef($intRef)
    {
        $this->intRef = $intRef;
        return $this;
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
     * Generate signature of data
     *
     * @return string
     * @throws Exceptions\SignatureException
     * @throws ParameterValidationException
     */
    public function generateSignature()
    {
        $this->validateRequiredParameters();

        if ($this->isSigningSchemaMacExtended()) {
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

        if ($this->isSigningSchemaMacAdvanced()) {
            return $this->getPrivateSignature([
                $this->getTerminalID(),
                $this->getTransactionType()->getValue(),
                $this->getAmount(),
                $this->getCurrency(),
                $this->getOrder(),
                $this->getSignatureTimestamp(),
                $this->getNonce()
            ]);
        }

        // Default MAC_GENERAL
        return $this->getPrivateSignature([
            $this->getTerminalID(),
            $this->getTransactionType()->getValue(),
            $this->getAmount(),
            $this->getCurrency(),
            $this->getOrder(),
            $this->getSignatureTimestamp(),
            $this->getNonce(),
            /**
             * ВАЖНО: В настоящата версия на интерфейса значението на поле RFU (Reserved
             * for Future Use) в символния низ за подписване е един байт 0x2D (знак минус "-").
             * Поле RFU е запазено за бъдещо ползване в символния низ за подпис и не участва
             * в заявката или отговора към/от APGW
             */
            '-'
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

        if (empty($this->getMerchantId())) {
            throw new ParameterValidationException('Merchant ID is empty!');
        }

        if (empty($this->getTerminalID())) {
            throw new ParameterValidationException('TerminalID is empty!');
        }
    }

    /**
     * Return the request type.
     *
     * @return RequestType
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * Set the request type
     *
     * @param RequestType $requestType
     * @return Request
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
        return $this;
    }

    /**
     * @return string|array
     * @throws Exceptions\SignatureException
     * @throws ParameterValidationException
     */
    public function generateForm()
    {
        return $this->getRequestType()
            ->setUrl($this->getEnvironmentUrl())
            ->setData($this->getData())
            ->generateForm();
    }

    /**
     * Send data to borica
     *
     * @return void|string|Response
     * @throws Exceptions\SignatureException|ParameterValidationException|SendingException
     */
    public function send()
    {
        return $this->requestType
            ->setUrl($this->getEnvironmentUrl())
            ->setData($this->getData())
            ->send();
    }
}

<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

use GuzzleHttp\Client;
use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;

/**
 * Borica request
 */
abstract class Request
{
    /**
     * @var mixed
     */
    private $signatureTimestamp;

    /**
     * @var string
     */
    private $terminalID;
    /**
     * @var string
     */
    private $privateKey;
    /**
     * @var string|null
     */
    private $privateKeyPassword = null;
    /**
     * @var string[]
     */
    private $environmentUrls = [
        'development' => 'https://3dsgate-dev.borica.bg/cgi-bin/cgi_link',
        'production' => 'https://3dsgate.borica.bg/cgi-bin/cgi_link'
    ];
    /**
     * In develop mode of application
     * @var string
     */
    private $environment = 'development';
    /**
     * @var string
     */
    private $backRefUrl;

    /**
     * @var string|float|integer
     */
    private $amount = null;

    /**
     * @var string
     */
    private $currency = 'BGN';

    /**
     * @var string
     */
    private $description;

    /**
     * @var TransactionType
     */
    private $transactionType;

    /**
     * @var mixed
     */
    private $order;

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description Описание на поръчката.
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
     * @return string
     */
    public function getBackRefUrl()
    {
        return $this->backRefUrl;
    }

    /**
     * @param string $backRefUrl URL на търговеца за изпращане на резултата от авторизацията.
     * @return Request
     */
    public function setBackRefUrl($backRefUrl)
    {
        $this->backRefUrl = $backRefUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order Номер на поръчката за търговеца, 6 цифри, който трябва да бъде уникален за деня.
     * @return Request
     * @throws ParameterValidationException
     */
    public function setOrder($order)
    {
        if (mb_strlen($order) > 6) {
            throw new ParameterValidationException('Order must be max 6 digits');
        }

        $this->order = $order;
        return $this;
    }

    /**
     * Switch to development mode
     * @return void
     */
    public function inDevelopment()
    {
        $this->environment = 'development';
    }

    /**
     * @return boolean
     */
    public function isProduction()
    {
        return $this->environment == 'production';
    }

    /**
     * @return string
     */
    public function getEnvironmentUrl()
    {
        if ($this->environment == 'development') {
            return $this->environmentUrls['development'];
        }
        return $this->environmentUrls['production'];
    }

    /**
     * @return mixed
     */
    public function getTerminalID()
    {
        return $this->terminalID;
    }

    /**
     * @param string $terminalID Terminal ID.
     * @return Request
     * @throws ParameterValidationException
     */
    public function setTerminalID($terminalID)
    {
        if (mb_strlen($terminalID) != 8) {
            throw new ParameterValidationException('Terminal ID must be exact 8 characters!');
        }
        $this->terminalID = $terminalID;
        return $this;
    }

    /**
     * @return TransactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param TransactionType $transactionType Тип на транзакцията.
     * @return Request
     */
    public function setTransactionType(TransactionType $transactionType)
    {
        $this->transactionType = $transactionType;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string|float|integer $amount Обща стойност на поръчката по стандарт ISO_4217 с десетичен разделител точка.
     * @return Request
     */
    public function setAmount($amount)
    {
        $this->amount = number_format($amount, 2, '.', '');
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency Валута на поръчката: три буквен код на валута по стандарт ISO 4217.
     * @return Request
     * @throws ParameterValidationException
     */
    public function setCurrency($currency)
    {
        if (mb_strlen($currency) != 3) {
            throw new ParameterValidationException('3 character currency code');
        }
        $this->currency = $currency;
        return $this;
    }

    /**
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
     * @param string|null $signatureTimestamp Дата на подпис/изпращане на данните.
     * @return Request
     */
    public function setSignatureTimestamp($signatureTimestamp = null)
    {
        if (empty($signatureTimestamp)) {
            $this->signatureTimestamp = date('YmdHis');
            return $this;
        }

        $this->signatureTimestamp = $signatureTimestamp;
        return $this;
    }

    /**
     * @param array $options Options for Client.
     * @return Client
     */
    protected function getGuzzleClient(array $options = [])
    {
        return new Client($options);
    }

    /**
     * Switch to production mode
     * @return void
     */
    protected function inProduction()
    {
        $this->environment = 'production';
    }

    /**
     * @param array $data Данни върху които да генерира подписа.
     * @return string
     * @throws SignatureException
     */
    protected function getSignature(array $data)
    {
        /*
         * generate signature
         */
        $signature = '';
        foreach ($data as $value) {
            $signature .= mb_strlen($value) . $value;
        }

        /*
         * sign signature
         */
        $privateKey = openssl_get_privatekey('file://' . $this->getPrivateKey(), $this->getPrivateKeyPassword());
        if (!$privateKey) {
            throw new SignatureException(openssl_error_string());
        }

        $openSignStatus = openssl_sign($signature, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (!$openSignStatus) {
            throw new SignatureException(openssl_error_string());
        }

        if (PHP_MAJOR_VERSION < 8) {
            /**
             * @deprecated in PHP 8.0
             * @note The openssl_pkey_free() function is deprecated and no longer has an effect,
             * instead the OpenSSLAsymmetricKey instance is automatically destroyed if it is no
             * longer referenced.
             * @see https://github.com/php/php-src/blob/master/UPGRADING#L397
             */
            openssl_pkey_free($privateKey);
        }

        return strtoupper(bin2hex($signature));
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param string      $privateKeyPath Път до файла на частният ключ.
     * @param string|null $password       Парола на частният ключ.
     * @return Request
     */
    public function setPrivateKey($privateKeyPath, $password = null)
    {
        $this->privateKey = $privateKeyPath;

        if (!empty($password)) {
            $this->setPrivateKeyPassword($password);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrivateKeyPassword()
    {
        return $this->privateKeyPassword;
    }

    /**
     * @param string|null $privateKeyPassword Парола на частният ключ.
     * @return Request
     */
    public function setPrivateKeyPassword($privateKeyPassword)
    {
        $this->privateKeyPassword = $privateKeyPassword;
        return $this;
    }
}

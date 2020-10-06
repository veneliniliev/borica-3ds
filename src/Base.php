<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Exceptions\SignatureException;

/**
 * Borica base
 */
abstract class Base
{
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
     * Generate signature of data with private key
     * @param array $data Данни върху които да генерира подписа.
     * @return string
     * @throws SignatureException
     */
    protected function getPrivateSignature(array $data)
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
     * Get private key
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set private key
     * @param string      $privateKeyPath Път до файла на частният ключ.
     * @param string|null $password       Парола на частният ключ.
     * @return Base
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
     * Get private key password
     * @return string|null
     */
    public function getPrivateKeyPassword()
    {
        return $this->privateKeyPassword;
    }

    /**
     * Set private key password
     * @param string|null $privateKeyPassword Парола на частният ключ.
     * @return Base
     */
    public function setPrivateKeyPassword($privateKeyPassword)
    {
        $this->privateKeyPassword = $privateKeyPassword;
        return $this;
    }

    /**
     * Switch to production mode
     * @return void
     */
    protected function inProduction()
    {
        $this->environment = 'production';
    }
}

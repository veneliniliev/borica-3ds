<?php
/*
 * Copyright (c) 2021. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\Exceptions\SignatureException;

/**
 * Borica base
 */
abstract class Base
{

    const SIGNING_SCHEMA_MAC_ADVANCED = 'MAC_ADVANCED';
    const SIGNING_SCHEMA_MAC_EXTENDED = 'MAC_EXTENDED';

    /**
     * Default signing schema
     *
     * @var string
     */
    protected $signingSchema = self::SIGNING_SCHEMA_MAC_ADVANCED;

    /**
     * @var string
     */
    protected $merchantId;

    /**
     * @var string
     */
    protected $publicKey;

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
     *
     * @var string
     */
    private $environment = 'production';

    /**
     * @return boolean
     */
    public function isProduction()
    {
        return $this->environment == 'production';
    }

    /**
     * @return boolean
     */
    public function isDevelopment()
    {
        return $this->environment == 'development';
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
     * Switch environment to development/production
     *
     * @param boolean $production True - production / false - development.
     *
     * @return Base
     */
    public function setEnvironment($production = true)
    {
        if ($production) {
            $this->inProduction();
            return $this;
        }
        $this->inDevelopment();
        return $this;
    }

    /**
     * Switch to production mode
     *
     * @return Base
     */
    public function inProduction()
    {
        $this->environment = 'production';
        return $this;
    }

    /**
     * Switch to development mode
     *
     * @return Base
     */
    public function inDevelopment()
    {
        $this->environment = 'development';
        return $this;
    }

    /**
     * Get terminal ID
     *
     * @return mixed
     */
    public function getTerminalID()
    {
        return $this->terminalID;
    }

    /**
     * Set terminal ID
     *
     * @param string $terminalID Terminal ID.
     *
     * @return Base
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
     * Get merchant ID
     *
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * Set merchant ID
     *
     * @param mixed $merchantId Merchant ID.
     *
     * @return Base
     * @throws ParameterValidationException
     */
    public function setMerchantId($merchantId)
    {
        if (mb_strlen($merchantId) < 10 || mb_strlen($merchantId) > 15) {
            throw new ParameterValidationException('Merchant ID must be 10-15 characters');
        }
        $this->merchantId = $merchantId;
        return $this;
    }

    /**
     * Switch signing schema to MAC_ADVANCED
     *
     * @return Base
     */
    public function setSigningSchemaMacAdvanced()
    {
        $this->signingSchema = self::SIGNING_SCHEMA_MAC_ADVANCED;
        return $this;
    }

    /**
     * Switch signing schema to MAC_EXTENDED
     *
     * @return Base
     */
    public function setSigningSchemaMacExtended()
    {
        $this->signingSchema = self::SIGNING_SCHEMA_MAC_EXTENDED;
        return $this;
    }

    /**
     * Get public key
     *
     * @return string
     * @throws ParameterValidationException
     */
    public function getPublicKey()
    {
        if (empty($this->publicKey)) {
            throw new ParameterValidationException('Please set public key first!');
        }

        return $this->publicKey;
    }

    /**
     * Set public key
     *
     * @param string $publicKey Public key path.
     *
     * @return Base
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    /**
     * Is MAC_ADVANCE signing schema?
     *
     * @return boolean
     */
    protected function isSigningSchemaMacAdvanced()
    {
        return $this->signingSchema == self::SIGNING_SCHEMA_MAC_ADVANCED;
    }

    /**
     * Generate signature of data with private key
     *
     * @param array $data Данни върху които да генерира подписа.
     *
     * @return string
     * @throws SignatureException
     */
    protected function getPrivateSignature(array $data)
    {
        $signature = $this->getSignatureSource($data);

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
             * @note       The openssl_pkey_free() function is deprecated and no longer has an effect,
             * instead the OpenSSLAsymmetricKey instance is automatically destroyed if it is no
             * longer referenced.
             * @see        https://github.com/php/php-src/blob/master/UPGRADING#L397
             */
            openssl_pkey_free($privateKey);
        }

        return strtoupper(bin2hex($signature));
    }

    /**
     * Generate signature source
     *
     * @param array   $data       Data of signature.
     * @param boolean $isResponse Generate signature from response.
     *
     * @return string
     */
    protected function getSignatureSource(array $data, $isResponse = false)
    {
        $signature = '';
        foreach ($data as $value) {
            if ($isResponse && mb_strlen($value) == 0) {
                $signature .= '-';
                continue;
            }
            $signature .= mb_strlen($value) . $value;
        }
        return $signature;
    }

    /**
     * Get private key
     *
     * @return string
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Set private key
     *
     * @param string      $privateKeyPath Път до файла на частният ключ.
     * @param string|null $password       Парола на частният ключ.
     *
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
     *
     * @return string|null
     */
    public function getPrivateKeyPassword()
    {
        return $this->privateKeyPassword;
    }

    /**
     * Set private key password
     *
     * @param string|null $privateKeyPassword Парола на частният ключ.
     *
     * @return Base
     */
    public function setPrivateKeyPassword($privateKeyPassword)
    {
        $this->privateKeyPassword = $privateKeyPassword;
        return $this;
    }
}

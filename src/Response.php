<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Exceptions\SignatureException;

abstract class Response extends Base
{

    /**
     * @var string
     */
    protected $publicKey;

    /**
     * Verify data with public certificate
     * @param array  $data            Данни върху които да генерира подписа.
     * @param string $publicSignature Публичен подпис.
     * @return void
     * @throws SignatureException
     */
    protected function verifyPublicSignature(array $data, $publicSignature)
    {
        /*
         * generate signature
         */
        $signature = '';
        foreach ($data as $value) {
            $signature .= mb_strlen($value) . $value;
        }

        /*
         * Open certificate file
         */
        $fp = fopen($this->getPublicKey(), "r");
        $publicKeyContent = fread($fp, filesize($this->getPublicKey()));
        fclose($fp);

        /*
         * sign signature
         */
        $publicKey = openssl_get_publickey($publicKeyContent);
        if (!$publicKey) {
            throw new SignatureException(openssl_error_string());
        }

        $verifyStatus = openssl_verify($signature, hex2bin($publicSignature), $publicKey, OPENSSL_ALGO_SHA256);
        if ($verifyStatus !== 1) {
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
            openssl_pkey_free($publicKey);
        }
    }

    /**
     * Get public key
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Set public key
     * @param string $publicKey Public key path.
     * @return Response
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
        return $this;
    }
}

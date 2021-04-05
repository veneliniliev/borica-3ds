<?php
/*
 * Copyright (c) 2021. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Exceptions\DataMissingException;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;

/**
 * Class Sale
 *
 * @package VenelinIliev\Borica3ds
 */
class SaleResponse extends Response implements ResponseInterface
{
    /**
     * Is success payment?
     *
     * @return boolean
     * @throws DataMissingException
     * @throws ParameterValidationException
     * @throws Exceptions\SignatureException
     */
    public function isSuccessful()
    {
        return $this->getResponseCode() === '00';
    }

    /**
     * Get response code - value of 'RC' field
     *
     * @return string
     * @throws Exceptions\SignatureException|ParameterValidationException|DataMissingException
     */
    public function getResponseCode()
    {
        return $this->getVerifiedData('RC');
    }
}

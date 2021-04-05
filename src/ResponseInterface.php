<?php
/*
 * Copyright (c) 2021. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

/**
 * Interface ResponseInterface
 *
 * @package VenelinIliev\Borica3ds
 */
interface ResponseInterface
{
    /**
     * @return array
     */
    public function getResponseData();

    /**
     * @param string $key Data key.
     *
     * @return mixed
     */
    public function getVerifiedData($key);
}

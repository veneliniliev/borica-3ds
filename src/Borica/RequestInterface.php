<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

/**
 * Interface RequestInterface
 * @package VenelinIliev\Borica3ds
 */
interface RequestInterface
{

    /**
     * Get data with post inputs
     * @return array
     */
    public function getData();

    /**
     * Send request
     * @return void
     */
    public function send();

    /**
     * Sign request
     * @return string
     */
    public function generateSignature();

    /**
     * Validate required data before sending
     * @return void
     */
    public function validateRequiredParameters();
}

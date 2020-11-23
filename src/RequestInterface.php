<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

/**
 * Interface RequestInterface
 *
 * @package VenelinIliev\Borica3ds
 */
interface RequestInterface
{

    /**
     * Get data with post inputs
     *
     * @return array
     */
    public function getData();

    /**
     * Generate html form and send request with js
     *
     * @return void|Response
     */
    public function send();

    /**
     * Generate hidden html form without submit
     *
     * @return mixed
     */
    public function generateForm();

    /**
     * Sign request
     *
     * @return string
     */
    public function generateSignature();

    /**
     * Validate required data before sending
     *
     * @return void
     */
    public function validateRequiredParameters();
}

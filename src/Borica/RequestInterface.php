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
    public function getData(): array;

    /**
     * Send request
     * @return void
     */
    public function send(): void;

    /**
     * Sign request
     * @return string
     */
    public function generateSignature(): string;

    /**
     * Validate required data before sending
     * @return void
     */
    public function validateRequiredParameters(): void;
}

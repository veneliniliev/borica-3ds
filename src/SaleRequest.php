<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;
use VenelinIliev\Borica3ds\RequestTypes\HtmlForm;

/**
 * Class Sale
 *
 * @package VenelinIliev\Borica3ds
 */
class SaleRequest extends Request implements RequestInterface
{
    /**
     * Sale constructor.
     */
    public function __construct()
    {
        $this->setTransactionType(TransactionType::SALE());
        $this->setRequestType(new HtmlForm());
    }

    /**
     * Send to borica. Generate form and auto submit with JS.
     *
     * @return void
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    public function send()
    {
        $html = parent::send();
        die($html);
    }

    /**
     * Get data required for request to borica
     *
     * @return array
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    public function getData()
    {
        return array_filter([
                'NONCE' => $this->getNonce(),
                'P_SIGN' => $this->generateSignature(),

                'TRTYPE' => $this->getTransactionType()->getValue(),
                'COUNTRY' => $this->getCountryCode(),
                'CURRENCY' => $this->getCurrency(),
                'LANG' => $this->getLang(),

                'MERCH_GMT' => $this->getMerchantGMT(),
                'MERCHANT' => $this->getMerchantId(),
                'MERCH_NAME' => $this->getMerchantName(),
                'MERCH_URL' => $this->getMerchantUrl(),
                'EMAIL' => $this->getEmailAddress(),

                'ORDER' => $this->getOrder(),
                'AMOUNT' => $this->getAmount(),
                'DESC' => $this->getDescription(),
                'TIMESTAMP' => $this->getSignatureTimestamp(),

                'TERMINAL' => $this->getTerminalID(),

                'M_INFO' => $this->getMInfo(),

            ]) + $this->generateAdCustBorOrderId();
    }
}

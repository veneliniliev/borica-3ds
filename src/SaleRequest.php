<?php
/*
 * Copyright (c) 2023. Venelin Iliev.
 * https://veneliniliev.com
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;

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
    }

    /**
     * Send to borica. Generate form and auto submit with JS.
     *
     * @return void
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    public function send()
    {
        $html = $this->generateForm();

        $html .= '<script>
            document.getElementById("borica3dsRedirectForm").submit()
        </script>';

        die($html);
    }

    /**
     * Generate HTML hidden form
     *
     * @return string
     * @throws Exceptions\SignatureException|ParameterValidationException
     */
    public function generateForm()
    {
        $html = '<form 
	        action="' . $this->getEnvironmentUrl() . '" 
	        style="display: none;" 
	        method="POST" 
	        id="borica3dsRedirectForm"
        >';

        $inputs = $this->getData();
        foreach ($inputs as $key => $value) {
            $html .= '<input type="hidden" name="' . $key . '" value="' . $value . '">';
        }

        $html .= '</form>';

        return $html;
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
                'BACKREF' => $this->getBackRefUrl(),

                'M_INFO' => $this->getMInfo(),

            ]) + $this->generateAdCustBorOrderId();
    }

    /**
     * Generate signature of data
     *
     * @return string
     * @throws Exceptions\SignatureException
     * @throws ParameterValidationException
     */
    public function generateSignature()
    {
        $this->validateRequiredParameters();

        if ($this->isSigningSchemaMacExtended()) {
            return $this->getPrivateSignature([
                $this->getTerminalID(),
                $this->getTransactionType()->getValue(),
                $this->getAmount(),
                $this->getCurrency(),
                $this->getOrder(),
                $this->getMerchantId(),
                $this->getSignatureTimestamp(),
                $this->getNonce()
            ]);
        }

        if ($this->isSigningSchemaMacAdvanced()) {
            return $this->getPrivateSignature([
                $this->getTerminalID(),
                $this->getTransactionType()->getValue(),
                $this->getAmount(),
                $this->getCurrency(),
                $this->getOrder(),
                $this->getSignatureTimestamp(),
                $this->getNonce()
            ]);
        }

        // Default MAC_GENERAL
        return $this->getPrivateSignature([
            $this->getTerminalID(),
            $this->getTransactionType()->getValue(),
            $this->getAmount(),
            $this->getCurrency(),
            $this->getOrder(),
            $this->getSignatureTimestamp(),
            $this->getNonce(),
            /**
             * ВАЖНО: В настоящата версия на интерфейса значението на поле RFU (Reserved
             * for Future Use) в символния низ за подписване е един байт 0x2D (знак минус "-").
             * Поле RFU е запазено за бъдещо ползване в символния низ за подпис и не участва
             * в заявката или отговора към/от APGW
             */
            '-'
        ]);
    }

    /**
     * Validate required fields to post
     *
     * @return void
     * @throws ParameterValidationException
     */
    public function validateRequiredParameters()
    {
        if (empty($this->getTransactionType())) {
            throw new ParameterValidationException('Transaction type is empty!');
        }

        if (empty($this->getAmount())) {
            throw new ParameterValidationException('Amount is empty!');
        }

        if (empty($this->getCurrency())) {
            throw new ParameterValidationException('Currency is empty!');
        }

        if (empty($this->getOrder())) {
            throw new ParameterValidationException('Order is empty!');
        }

        if (empty($this->getDescription())) {
            throw new ParameterValidationException('Description is empty!');
        }

        if (empty($this->getBackRefUrl()) && $this->isDevelopment()) {
            throw new ParameterValidationException('Back ref url is empty! (required in development)');
        }

        if (empty($this->getMerchantId())) {
            throw new ParameterValidationException('Merchant ID is empty!');
        }

        if (empty($this->getTerminalID())) {
            throw new ParameterValidationException('TerminalID is empty!');
        }
    }
}

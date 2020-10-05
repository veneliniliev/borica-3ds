<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds;

use VenelinIliev\Borica3ds\Enums\TransactionType;
use VenelinIliev\Borica3ds\Exceptions\ParameterValidationException;

/**
 * Borica request
 */
abstract class Request extends Base
{
    /**
     * @var mixed
     */
    private $signatureTimestamp;

    /**
     * @var string
     */
    private $terminalID;

    /**
     * @var string
     */
    private $backRefUrl;

    /**
     * @var string|float|integer
     */
    private $amount = null;

    /**
     * @var string
     */
    private $currency = 'BGN';

    /**
     * @var string
     */
    private $description;

    /**
     * @var TransactionType
     */
    private $transactionType;

    /**
     * @var mixed
     */
    private $order;

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description Описание на поръчката.
     * @return Request
     * @throws ParameterValidationException
     */
    public function setDescription($description)
    {
        if (mb_strlen($description) > 50) {
            throw new ParameterValidationException('Description must be max 50 digits');
        }
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getBackRefUrl()
    {
        return $this->backRefUrl;
    }

    /**
     * @param string $backRefUrl URL на търговеца за изпращане на резултата от авторизацията.
     * @return Request
     */
    public function setBackRefUrl($backRefUrl)
    {
        $this->backRefUrl = $backRefUrl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order Номер на поръчката за търговеца, 6 цифри, който трябва да бъде уникален за деня.
     * @return Request
     * @throws ParameterValidationException
     */
    public function setOrder($order)
    {
        if (mb_strlen($order) > 6) {
            throw new ParameterValidationException('Order must be max 6 digits');
        }

        $this->order = str_pad($order, 6, "0", STR_PAD_LEFT);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTerminalID()
    {
        return $this->terminalID;
    }

    /**
     * @param string $terminalID Terminal ID.
     * @return Request
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
     * @return TransactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param TransactionType $transactionType Тип на транзакцията.
     * @return Request
     */
    public function setTransactionType(TransactionType $transactionType)
    {
        $this->transactionType = $transactionType;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string|float|integer $amount Обща стойност на поръчката по стандарт ISO_4217 с десетичен разделител точка.
     * @return Request
     */
    public function setAmount($amount)
    {
        $this->amount = number_format($amount, 2, '.', '');
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency Валута на поръчката: три буквен код на валута по стандарт ISO 4217.
     * @return Request
     * @throws ParameterValidationException
     */
    public function setCurrency($currency)
    {
        if (mb_strlen($currency) != 3) {
            throw new ParameterValidationException('3 character currency code');
        }
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureTimestamp()
    {
        if (empty($this->signatureTimestamp)) {
            $this->setSignatureTimestamp();
        }

        return $this->signatureTimestamp;
    }

    /**
     * @param string|null $signatureTimestamp Дата на подпис/изпращане на данните.
     * @return Request
     */
    public function setSignatureTimestamp($signatureTimestamp = null)
    {
        if (empty($signatureTimestamp)) {
            $this->signatureTimestamp = date('YmdHis');
            return $this;
        }

        $this->signatureTimestamp = $signatureTimestamp;
        return $this;
    }
}

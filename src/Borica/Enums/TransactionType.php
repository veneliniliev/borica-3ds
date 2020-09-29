<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class TransactionType
 * @package VenelinIliev\Borica3ds\Enums
 */
class TransactionType extends Enum
{
    private const SALE = 1;
    private const DEFERRED_AUTHORIZATION = 12;
    private const COMPLETION_DEFERRED_AUTHORIZATION = 21;
    private const REVERSAL_REQUEST = 22;
    private const REVERSAL_ADVICE = 24;
    private const TRANSACTION_STATUS_CHECK = 90;
}

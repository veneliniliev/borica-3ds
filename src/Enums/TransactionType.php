<?php
/*
 * Copyright (c) 2020. Venelin Iliev.
 */

namespace VenelinIliev\Borica3ds\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class TransactionType
 * @package VenelinIliev\Borica3ds\Enums
 * @method static SALE()
 * @method static TRANSACTION_STATUS_CHECK()
 */
class TransactionType extends Enum
{
    const SALE = 1;
    const DEFERRED_AUTHORIZATION = 12;
    const COMPLETION_DEFERRED_AUTHORIZATION = 21;
    const REVERSAL_REQUEST = 22;
    const REVERSAL_ADVICE = 24;
    const TRANSACTION_STATUS_CHECK = 90;
}

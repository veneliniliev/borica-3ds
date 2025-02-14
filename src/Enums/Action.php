<?php

namespace VenelinIliev\Borica3ds\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class Action
 * @package VenelinIliev\Borica3ds\Enums
 * @method static SUCCESS()
 * @method static DUPLICATE()
 * @method static DECLINE()
 * @method static PROCESSING_ERROR()
 * @method static DUPLICATE_DECLINE()
 * @method static DUPLICATE_AUTHENTICATION_ERROR()
 * @method static DUPLICATE_NO_RESPONSE()
 * @method static SOFT_DECLINE()
 */

class Action extends Enum
{
    /**
     * Transaction successfully completed.
     */
    const SUCCESS = '0';

    /**
     * Duplicate transaction found.
     */
    const DUPLICATE = '1';

    /**
     * Transaction declined, original issuer’s response is returned
     */
    const DECLINE = '2';

    /**
     * Transaction processing error
     */
    const PROCESSING_ERROR = '3';

    /**
     * Duplicate, declined transaction
     */
    const DUPLICATE_DECLINE = '6';

    /**
     * Duplicate, authentication error
     */
    const DUPLICATE_AUTHENTICATION_ERROR = '7';

    /**
     * Duplicate, no response
     */
    const DUPLICATE_NO_RESPONSE = '8';

    /**
     * Soft Decilne
     */
    const SOFT_DECLINE = '21';
}

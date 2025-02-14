<?php

namespace VenelinIliev\Borica3ds\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class ResponseCode
 * @package VenelinIliev\Borica3ds\Enums
 * @method static SUCCESS()
 * @method static SOFT_DECLINE()
 * @method static SOFT_DECLINE_VISA()
 * @method static SOFT_DECLINE_MASTERCARD()
 * @method static MISSING_MANDATORY_FIELD()
 * @method static CGI_VALIDATION_ERROR()
 * @method static BAD_SIGNATURE()
 * @method static UNSUCCESSFUL_AUTHENTICATION()
 * @method static TRANSACTION_DATA_MISMATCH()
 * @method static IN_PROGRESS_ISSUER()
 * @method static IN_PROGRESS_AUTHENTICATION()
 * @method static APPROVAL_CARDHOLDER()
 * @method static IN_PROGRESS_CLIENT_SIDE()
 */

class ResponseCode extends Enum
{
    /**
     * Transaction successfully completed.
     */
    const SUCCESS = '00';

    /**
     * Soft Decilne
     */
    const SOFT_DECLINE = '1A';

    /**
     * Soft Decilne for VISA
     */
    const SOFT_DECLINE_VISA = '1A';

    /**
     * Soft Decilne for Mastercard
     */
    const SOFT_DECLINE_MASTERCARD = '1A';

    /**
     * A mandatory request field is not filled in
     */
    const MISSING_MANDATORY_FIELD = '-1';

    /**
     * CGI request validation failed
     */
    const CGI_VALIDATION_ERROR = '-2';

    /**
     * The terminal is denied access to e-Gateway
     */
    const BAD_SIGNATURE = '-17';

    /**
     * Unsuccessful authentication. StatusMsg field contains additional details.
     */
    const UNSUCCESSFUL_AUTHENTICATION = '-19';

    /**
     * Transaction context data mismatch
     */
    const TRANSACTION_DATA_MISMATCH = '-24';

    /**
     * Transaction is processed by the Issuer
     */
    const IN_PROGRESS_ISSUER = '-31';

     /**
     * Authentication in progress
     */
    const IN_PROGRESS_AUTHENTICATION = '-33';

    /**
     * Request for approval of cardholder
     */
    const APPROVAL_CARDHOLDER = '-39';

    /**
     * Client side transaction form in progress
     */
    const IN_PROGRESS_CLIENT_SIDE = '-40';
}

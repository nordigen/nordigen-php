<?php

namespace Nordigen\NordigenPHP\Enums;

class AccountProcessingStatus
{
    /**
     * User has successfully authenticated themselves, and the account has been discovered.
     * @var string
     */
    public const DISCOVERED = 'DISCOVERED';

    /**
     * An error was encountered while processing the account.
     * @var string
     */
    public const PROCESSING = 'PROCESSING';

    /**
     * Account has been successfully processed.
     * @var string
     */
    public const READY      = 'READY';

    /**
     * An error was encountered while processing the account.
     * @var string
     */
    public const ERROR      = 'ERROR';

    /**
     * Account has been suspended (more than 10 consecutive failed attempts to access the account).
     * @var string
     */
    public const SUSPENDED  = 'SUSPENDED';

    /**
     * Access to account has expired as set in the End User Agreement.
     * @var string
     */
    public const EXPIRED    = 'EXPIRED';

}

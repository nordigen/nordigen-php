<?php

namespace Nordigen\NordigenPHP\Enums;

class RequisitionStatus
{
    /**
     * Requisition has been successfully created.
     * @var string
     */
    public const CREATED   = 'CR';

    /**
     * Account has been successfully linked to requisition.
     * @var string
     */
    public const LINKED    = 'LN';

    /**
     * Requisition is suspended due to numerous consecutive errors that happened while accessing its accounts.
     * @var string
     */
    public const SUSPENDED = 'SU';

    /**
     * End-user is giving consent at Nordigen's consent screen.
     * @var string
     */
    public const GIVING_CONSENT = 'GC';

    /**
     * End-user is redirected to the financial institution for authentication.
     * @var string
     */
    public const UNDERGOING_AUTHENTICATION = 'UA';


    /**
     * SSN verification has failed.
     * @var string
     */
    public const REJECTED = 'RJ';

    /**
     * End-user is selecting accounts.
     * @var string
     */
    public const SELECTING_ACCOUNTS = 'SA';

    /**
     * End-user is granting access to their account information.
     * @var string
     */
    public const GRANTING_ACCESS = 'GA';

    /**
     * 	Access to accounts has expired as set in End User Agreement.
     * @var string
     */
    public const EXPIRED = 'EX';

}

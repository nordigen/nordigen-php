<?php

namespace Nordigen\NordigenPHP\Enums;

class BalanceType
{
    /**
     * Balance of the account at the end of the pre-agreed account reporting period.
     * It is the sum of the opening booked balance at the beginning of the period and
     * all entries booked to the account during the pre-agreed account reporting period.
     *
     * For card-accounts, this is composed of:
     *  - invoiced, but not yet paid entries.
     * @var string
     */
    public const CLOSING_BOOKED = 'closingBooked';

    /**
     * Closing balance of amount of money that is at the disposal of the account owner on the date specified.
     * @var string
     */
    public const CLOSING_AVAILABLE = 'closingAvailable';

    /**
     * Balance composed of booked entries and pending items known at the time of calculation,
     * which projects the end of day balance if everything is booked
     * on the account and no other entry is posted.
     *
     * For card-accounts, this is composed of:
     *  - invoiced, but not yet paid entries,
     *  - not yet invoiced but already booked entries,
     *  - pending items (not yet booked)
     * @var string
     */
    public const EXPECTED = 'expected';

    /**
     * Book balance of the account at the beginning of the account reporting period.<br>
     * It always equals the closing book balance from the previous report.
     * @var string
     */
    public const OPENING_BOOKED = 'openingBooked';

    /**
     * Opening balance of amount of money that is at the disposal of the account owner on the date specified.
     * @var string
     */
    public const OPENING_AVAILABLE = 'openingAvailable';

    /**
     * Balance of the account at the previously closed account reporting period.<br>
     * The opening booked balance for the new period has to be equal to this balance.
     * @var string
     */
    public const PREVIOUSLY_CLOSING_BOOKED = 'previouslyClosingBooked';

    /**
     * Balance for informational purposes.
     * @var string
     */
    public const INFORMATION = 'information';

    /**
     * Available balance calculated in the course of the account servicer’s business day, at the time specified,
     * and subject to further changes during the business day.<br>
     * The interim balance is calculated on the basis of
     * booked credit and debit items during the calculation time/period specified.<br>
     * For card-accounts, this is composed of:
     *  - invoiced, but not yet paid entries,
     *  - not yet invoiced but already booked entries
     * @var string
     */
    public const INTERIM_AVAILABLE = 'interimAvailable';

    /**
     * Balance calculated in the course of the account servicer's business day, at the time specified,
     * and subject to further changes during the business day.<br>
     * The interim balance is calculated on the basis of
     * booked credit and debit items during the calculation time/period specified.
     * @var string
     */
    public const INTERIM_BOOKED = 'interimBooked';

    /**
     * Forward available balance of money that is at the disposal of the account owner on the date specified.
     * @var string
     */
    public const FORWARD_AVAILABLE = 'forwardAvailable';

    /**
     * Only for card accounts, to be defined yet.
     * @var string
     */
    public const NON_INVOICED = 'nonInvoiced';

    /**
     * Deprecated value. Analogous to interimBooked.
     * @var string
     */
    public const AUTHORIZED = 'authorized';

}

<?php

namespace Nordigen\NordigenPHP\Exceptions\InstitutionExceptions;

/**
 * Access to the account has expired or it has been revoked. To restore access reconnect the account.
 */
class AccessExpiredError extends InstitutionException {}
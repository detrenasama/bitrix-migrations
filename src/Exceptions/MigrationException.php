<?php

namespace Detrena\BitrixMigrations\Exceptions;

use Exception;

class MigrationException extends Exception
{
    protected $code = 1;
}

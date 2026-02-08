<?php

namespace App\Exceptions;

use Exception;

class AppLogicException extends Exception
{
    protected $code = 500;
}

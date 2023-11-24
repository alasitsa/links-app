<?php

namespace App\Exceptions;

use Exception;

class LoginFailed extends Exception
{
    private const MESSAGE = "Login failed";

    public function __construct()
    {
        parent::__construct($this::MESSAGE);
    }
}

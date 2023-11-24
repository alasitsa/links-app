<?php

namespace App\Exceptions;

use Exception;

class LinkUnsafeException extends Exception
{
    private const MESSAGE = "Link is unsafe";

    public function __construct()
    {
        parent::__construct($this::MESSAGE);
    }
}

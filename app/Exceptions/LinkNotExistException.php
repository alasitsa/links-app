<?php

namespace App\Exceptions;

use Exception;

class LinkNotExistException extends Exception
{
    private const MESSAGE = "Link does not exists";

    public function __construct()
    {
        parent::__construct($this::MESSAGE);
    }
}

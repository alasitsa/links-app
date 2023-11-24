<?php

namespace App\Exceptions;

use Exception;

class LinkExistsException extends Exception
{
    private const MESSAGE = "Link has already exists";

    public function __construct()
    {
        parent::__construct($this::MESSAGE);
    }
}

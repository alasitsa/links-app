<?php

namespace App\Exceptions;

use Exception;

class LinkForbidden extends Exception
{
    private const MESSAGE = "Forbidden";

    public function __construct()
    {
        parent::__construct($this::MESSAGE);
    }
}

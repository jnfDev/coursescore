<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ModelCannotBeDeletedException extends Exception 
{
    public function __construct(
        string $message = "", 
        int $code = 0, 
        Throwable|null $previous = null
    ) {
        $message = empty( $message ) ? 'Model cannot be deleted due to a constraint relation.' : $message;
        parent::__construct( $message, $code, $previous );
    }
}
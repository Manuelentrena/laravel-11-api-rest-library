<?php

namespace App\Exceptions\API\V1;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CreateLoanException extends Exception
{
    protected $message = 'Fail to create Loan or subtract stock.';
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;


    public function __construct($message = null, $code = null, Exception $previous = null)
    {
        if ($message) {
            $this->message = $message;
        }

        if ($code) {
            $this->code = $code;
        }

        parent::__construct($this->message, $this->code, $previous);
    }
}

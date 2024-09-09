<?php

namespace App\Exceptions\API\V1;

use Exception;

class UpdateLoanException extends Exception
{
    protected $message = 'Fail to update Loan or add stock.';
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

<?php

declare(strict_types=1);

namespace App\Exception;


use App\Com\ResponseCode;
use Hyperf\Server\Exception\ServerException;
use Throwable;

class BusinessException extends ServerException
{
    public function __construct($code = 0, string $message = null, Throwable $previous = null)
    {

        if ($code == 0) {
            $code = ResponseCode::UNKNOWN_ERROR;
        }

        if (is_null($message)) {
            $message = ResponseCode::getMessage($code);
        }

        parent::__construct($message, $code, $previous);
    }
}

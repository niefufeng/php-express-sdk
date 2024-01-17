<?php

namespace NieFufeng\Express\Exceptions;

/**
 * 接口异常
 */
class ApiException extends Exception
{
    public function __construct(string $message, public readonly array $result)
    {
        parent::__construct($message, 500);
    }
}

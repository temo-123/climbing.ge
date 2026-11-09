<?php

namespace App\Services;

/**
 * A rejected order (bad input, stock, region rule…). Thrown inside
 * OrderPlacementService::place()'s transaction, so nothing is written.
 * Controllers turn it into a JSON error: { error: message, code: reason }.
 */
class OrderPlacementException extends \RuntimeException
{
    public function __construct(string $message, public readonly string $reason = 'invalid', public readonly int $status = 422)
    {
        parent::__construct($message);
    }

    public function response()
    {
        return response()->json(['error' => $this->getMessage(), 'code' => $this->reason], $this->status);
    }
}

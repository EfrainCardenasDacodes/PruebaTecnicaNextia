<?php

namespace App\Core\Data;

use JsonSerializable;

class ApiResponse implements JsonSerializable
{
    /**
     * Status response
     *
     * @var int
     */
    private $status;

    /**
     * Data response
     *
     * @var mixed
     */
    private $data;

    /**
     * Api response constructor
     *
     * @param integer $status
     * @param mixed $data
     */
    function __construct(int $status, $data)
    {
        $this->status = $status;
        $this->data = $data;
    }

    function jsonSerialize()
    {
        return [
            'status' => $this->status,
            'data' => $this->data
        ];
    }
}

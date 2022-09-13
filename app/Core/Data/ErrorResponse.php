<?php

namespace App\Core\Data;

use JsonSerializable;

class ErrorResponse implements JsonSerializable
{
    /**
     * Data response
     *
     * @var mixed
     */
    private $data;

    /**
     * Data response
     *
     * @var mixed
     */
    private $errors;

    /**
     * Api response constructor
     *
     * @param integer $status
     * @param mixed $data
     */
    public function __construct($data=null, $errors=null)
    {
        $this->errors = $errors;
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        return [
            'message' => $this->data,
            'errors' => $this->errors
        ];
    }
}

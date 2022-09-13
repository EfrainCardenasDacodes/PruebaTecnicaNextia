<?php

namespace App\Core\Data;

use Illuminate\Database\Eloquent\Model;

interface Repository
{
    /**
     * Get model
     *
     * @return Model
     */
    function getModel(): Model;
}

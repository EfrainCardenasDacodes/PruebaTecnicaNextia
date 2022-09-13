<?php

namespace App\Core\Data;

trait Paginable
{
    /**
     * Indicates if a model is paginable or not
     *
     * @var boolean
     */
    public $withPagination = true;

    /**
     * Indicates the number of items for page
     *
     * @var integer
     */
    public $itemsPerPage = 10;
}

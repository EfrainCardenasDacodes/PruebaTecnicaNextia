<?php

namespace App\Repositories;

use App\Core\Data\BaseRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Statement repository interface
 */
class UserRepository extends BaseRepository
{
    /**
     * Get Statement model
     *
     * @return Model
     */
    function getModel(): Model
    {
        $model = User::class;
        return new $model;
    }
}

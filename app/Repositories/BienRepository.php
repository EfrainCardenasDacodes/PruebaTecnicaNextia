<?php

namespace App\Repositories;

use App\Core\Data\BaseRepository;
use App\Models\Bien;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Core\Data\ErrorResponse;

/**
 * Statement repository interface
 */
class BienRepository extends BaseRepository
{
    /**
     * Get Statement model
     *
     * @return Model
     */
    function getModel(): Model
    {
        $model = Bien::class;
        return new $model;
    }

    public function GetFromIds($ids)
    {
        try{
            $bienes = Bien::whereIn('id', $ids)->get();

            if($bienes){        
                return $bienes;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error occurred", [$e->getMessage()]), 400);
        }     
    }
}

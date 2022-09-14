<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bien extends Model
{
    protected $table = "Bienes";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'articulo', 'descripcion', 'usuario_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'usuario_id'
    ];

    public $filters = [
        'articulo', 'usuario_id', 'id'
    ];

    public function usuario(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'usuario_id');
    }
}

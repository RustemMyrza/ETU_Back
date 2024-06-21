<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfrastructurePage extends Model
{
    use HasFactory;

    protected $table = 'infrastructure_pages';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    public function getTitle()
    {
        return $this->hasOne(Translate::class, 'id', 'title');
    }

    // public function getImages()
    // {
    //     return $this->hasMany(InfrastructureSlider::class, 'parentId', 'id');
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfrastructureSlider extends Model
{
    use HasFactory;

    protected $table = 'infrastructure_sliders';

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
    protected $fillable = ['title', 'images'];

    public function getTitle()
    {
        return $this->hasOne(Translate::class, 'id', 'title');
    }
}

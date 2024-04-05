<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BachelorSchoolEducator extends Model
{
    use HasFactory;

    protected $table = 'bachelor_school_educators';

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
    protected $fillable = ['name', 'position', 'image', 'school_id'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function getPosition()
    {
        return $this->hasOne(Translate::class, 'id', 'position');
    }

    public function parent()
    {
        return $this->belongsTo(BachelorSchool::class);
    }
}

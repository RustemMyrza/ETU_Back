<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BachelorSchool extends Model
{
    use HasFactory;

    protected $table = 'bachelor_schools';

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
    protected $fillable = ['name', 'image'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function getSpecialties()
    {
        return $this->hasMany(BachelorSchoolSpecialty::class, 'school_id', 'id');
    }

    public function getEducators()
    {
        return $this->hasMany(BachelorSchoolEducator::class, 'school_id', 'id');
    }

    public function getPage()
    {
        return $this->hasMany(BachelorSchoolPage::class, 'school_id', 'id');
    }
}

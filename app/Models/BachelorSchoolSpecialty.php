<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BachelorSchoolSpecialty extends Model
{
    use HasFactory;

    protected $table = 'bachelor_school_specialties';

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
    protected $fillable = ['name', 'school_id'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function getPage()
    {
        return $this->hasMany(BachelorSchoolSpecialtyPage::class, 'specialty_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(BachelorSchool::class);
    }
}

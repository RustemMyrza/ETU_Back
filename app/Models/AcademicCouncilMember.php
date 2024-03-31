<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCouncilMember extends Model
{
    use HasFactory;

    protected $table = 'academic_council_members';

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
    protected $fillable = ['name', 'description'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function getDescription()
    {
        return $this->hasOne(Translate::class, 'id', 'description');
    }
}

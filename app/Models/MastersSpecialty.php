<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MastersSpecialty extends Model
{
    use HasFactory;

    protected $table = 'masters_specialties';

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
    protected $fillable = ['name'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function getPage()
    {
        return $this->hasMany(MastersSpecialtyPage::class, 'parent_id', 'id');
    }

    public function getMeta()
    {
        return $this->hasOne(MasterSpecialtyMeta::class, 'page_id', 'id');
    }
}

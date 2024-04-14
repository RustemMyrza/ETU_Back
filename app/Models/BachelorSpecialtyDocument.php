<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BachelorSpecialtyDocument extends Model
{
    use HasFactory;

    protected $table = 'bachelor_specialty_documents';

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
    protected $fillable = ['name', 'link', 'specialty_id'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }
}

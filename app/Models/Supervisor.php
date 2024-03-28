<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $table = 'supervisors';

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
    protected $fillable = ['name', 'position', 'email', 'phone', 'address', 'image'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function getPosition()
    {
        return $this->hasOne(Translate::class, 'id', 'position');
    }

    public function getAddress()
    {
        return $this->hasOne(Translate::class, 'id', 'address');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $table = 'costs';

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
    protected $fillable = ['program', 'first', 'second', 'third', 'fourth', 'total', 'type'];

    public function getProgram()
    {
        return $this->hasOne(Translate::class, 'id', 'program');
    }
}

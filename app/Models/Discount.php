<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

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
    protected $fillable = ['category', 'amount', 'note', 'student_type'];

    public function getCategory()
    {
        return $this->hasOne(Translate::class, 'id', 'category');
    }

    public function getNote()
    {
        return $this->hasOne(Translate::class, 'id', 'student_type');
    }
}

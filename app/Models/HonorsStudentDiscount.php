<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HonorsStudentDiscount extends Model
{
    protected $table = 'honors_student_discounts';

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
    protected $fillable = ['category', 'amount', 'note', 'gpa'];

    public function getCategory()
    {
        return $this->hasOne(Translate::class, 'id', 'category');
    }

    public function getNote()
    {
        return $this->hasOne(Translate::class, 'id', 'note');
    }
}

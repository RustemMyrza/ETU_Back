<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SciencePage extends Model
{
    use HasFactory;

    protected $table = 'science_pages';

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
    protected $fillable = ['tab_name'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'tab_name');
    }

    public function getParent()
    {
        return $this->hasMany(HeaderNavBar::class, 'id', 'parent_id');
    }
}

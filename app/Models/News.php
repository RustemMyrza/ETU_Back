<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

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
    protected $fillable = ['name', 'background_image', 'date'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function getChild()
    {
        return $this->hasOne(NewsContent::class, 'parent_id', 'id');
    }
}

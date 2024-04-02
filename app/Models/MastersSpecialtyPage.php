<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MastersSpecialtyPage extends Model
{
    use HasFactory;

    protected $table = 'masters_specialty_pages';

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
    protected $fillable = ['title', 'content', 'image', 'parent_id'];

    public function getTitle()
    {
        return $this->hasOne(Translate::class, 'id', 'title');
    }

    public function getContent()
    {
        return $this->hasOne(Translate::class, 'id', 'content');
    }

    public function parent()
    {
        return $this->belongsTo(MastersSpecialty::class);
    }
}

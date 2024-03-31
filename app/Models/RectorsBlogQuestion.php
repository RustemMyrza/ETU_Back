<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RectorsBlogQuestion extends Model
{
    use HasFactory;

    protected $table = 'rectors_blog_questions';

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
    protected $fillable = ['question', 'answer', 'author'];

    public function getTitle()
    {
        return $this->hasOne(Translate::class, 'id', 'title');
    }

    public function getContent()
    {
        return $this->hasOne(Translate::class, 'id', 'content');
    }
}

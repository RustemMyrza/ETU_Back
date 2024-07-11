<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsDocument extends Model
{
    protected $table = 'news_documents';

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
    protected $fillable = ['name', 'document_link'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function parent()
    {
        return $this->belongsTo(News::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsPageSlider extends Model
{
    use HasFactory;

    protected $table = 'news_page_sliders';

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
    protected $fillable = ['images', 'news_id'];

    public function getNews()
    {
        return $this->hasOne(News::class, 'id', 'news_id');
    }

    public function parent()
    {
        return $this->belongsTo(News::class);
    }
}

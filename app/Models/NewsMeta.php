<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsMeta extends MetaData
{
    use HasFactory;

    protected $table = 'news_metas';
}

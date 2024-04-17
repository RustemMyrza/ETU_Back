<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummerSchoolDocument extends Document
{
    use HasFactory;

    protected function getTableName()
    {
        // Переопределяем метод для установки названия таблицы
        return 'summer_school_documents';
    }
}

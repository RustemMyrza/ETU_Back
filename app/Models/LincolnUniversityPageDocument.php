<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LincolnUniversityPageDocument extends Document
{
    use HasFactory;

    protected function getTableName()
    {
        // Переопределяем метод для установки названия таблицы
        return 'lincoln_university_page_documents';
    }
}

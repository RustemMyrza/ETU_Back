<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccreditationPageDocument extends Document
{
    use HasFactory;
    

    protected function getTableName()
    {
        // Переопределяем метод для установки названия таблицы
        return 'accreditation_page_documents';
    }
}

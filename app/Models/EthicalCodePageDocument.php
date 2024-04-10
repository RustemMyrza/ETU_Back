<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EthicalCodePageDocument extends Document
{
    use HasFactory;

    protected function getTableName()
    {
        // Переопределяем метод для установки названия таблицы
        return 'ethical_code_page_documents';
    }
}

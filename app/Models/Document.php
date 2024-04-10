<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class Document extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'link'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Устанавливаем таблицу для каждой модели в соответствии с именем класса
        $this->table = $this->getTableName();
    }

    abstract protected function getTableName();
}

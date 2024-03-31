<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacancyApplication extends Model
{
    use HasFactory;

    protected $table = 'vacancy_applications';

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
    protected $fillable = ['name', 'phone', 'email', 'summary', 'letter', 'education', 'recommender', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Vacancy::class);
    }
}

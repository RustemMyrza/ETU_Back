<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScienceInnovationPageDocument extends Model
{
    use HasFactory;
    

    protected $table = 'science_innovation_page_documents';

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
    protected $fillable = ['name', 'link', 'block_id'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'name');
    }
}

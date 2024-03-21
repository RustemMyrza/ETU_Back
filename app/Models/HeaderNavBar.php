<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\AboutUniversityPageResource;
use App\Http\Resources\EnrollmentPageResource;
use App\Http\Resources\StudentsPageResource;
use App\Http\Resources\SchoolsPageResource;
use App\Http\Resources\SciencePageResource;

class HeaderNavBar extends Model
{
    use HasFactory;

    protected $table = 'header_nav_bars';

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
    protected $fillable = ['tab_name'];

    public function getName()
    {
        return $this->hasOne(Translate::class, 'id', 'tab_name');
    }

    public function getChild()
    {
        return $this->hasMany(AboutUniversityPage::class, 'parentId', 'id');
    }
}

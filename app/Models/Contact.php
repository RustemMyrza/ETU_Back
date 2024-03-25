<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contacts';

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
    protected $fillable = [
        'id', 
        'timestable',
        'tab_name',
        'address', 
        'admissions_committee_num_1', 
        'admissions_committee_num_2', 
        'admissions_committee_mail', 
        'rectors_reception_num', 
        'office_receptionist_num', 
        'tiktok_name', 
        'tiktok_link', 
        'instagram_name', 
        'instagram_link', 
        'facebook_link', 
        'youtube_link'
    ];

    public function getTabName()
    {
        return $this->hasOne(Translate::class, 'id', 'tab_name');
    }
}

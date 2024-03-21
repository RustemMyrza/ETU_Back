<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
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
    protected $fillable = ['id', 'timestable', 'director_name', 'director_phone_number', 'deputy_director_name', 'deputy_director_phone_number', 'email', 'reception', 'sales_department', 'email_elevator'];

    protected function get_director_name()
    {
        return $this->hasOne(Translate::class, 'id', 'director_name');
    }

    protected function get_director_num()
    {
        return $this->hasOne(Translate::class, 'id', 'director_phone_number');
    }

    protected function get_deputy_name()
    {
        return $this->hasOne(Translate::class, 'id', 'deputy_director_name');
    }

    protected function get_deputy_num()
    {
        return $this->hasOne(Translate::class, 'id', 'deputy_director_phone_number');
    }

    protected function get_email()
    {
        return $this->hasOne(Translate::class, 'id', 'email');
    }

    protected function get_reception()
    {
        return $this->hasOne(Translate::class, 'id', 'reception');
    }

    protected function get_sales_department()
    {
        return $this->hasOne(Translate::class, 'id', 'sales_department');
    }

    protected function get_email_elevator()
    {
        return $this->hasOne(Translate::class, 'id', 'email_elevator');
    }
}

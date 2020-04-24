<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'business';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'career'
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'business_company', 'business_id', 'company_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyEvents extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_events';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'event_id', 'chance', 'type', 'price'
    ];
}

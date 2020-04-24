<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_name', 'default_chance'
    ];

    public function business()
    {
        return $this->belongsToMany(Business::class, 'business_events', 'event_id', 'business_id')
            ->withPivot('type');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_events', 'event_id', 'company_id')
            ->withPivot('chance', 'type', 'price');
    }

    public function companyEvents()
    {
        return $this->hasMany(CompanyEvents::class, 'event_id', 'id');
    }
}

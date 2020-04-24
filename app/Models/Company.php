<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_name', 'shares_code', 'shares_qty', 'shares_price'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id')
            ->withPivot('shares_own');
    }

    public function business()
    {
        return $this->belongsToMany(Business::class, 'business_company', 'company_id', 'business_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'company_events', 'company_id', 'event_id')
            ->withPivot('chance', 'type', 'price');
    }
}

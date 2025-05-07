<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['stripe_charge_id' , 'amount' , 'currency' , 'status' , 'description' , 'user_id'];

    public function payment(){
        return $this->belongsTo(User::class);
    }
}

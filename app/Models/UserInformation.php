<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    protected $fillable = ['firstName' , 'lastName' , 'email' , 'phone' , 'address' , 'town' , 'state' , 'postal_code' , 'country' , 'user_id' , 'photo'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

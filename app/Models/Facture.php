<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id', 'date_facture','subtot','discount','tot','state'
    ];
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    function facture_details()
    {
        return $this->hasMany('App\Model\FactureDetail');
    }
    function products()
    {
        return $this->hasMany('App\Model\Products');
    }

    
   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;


    protected $fillable = [
            'invoice_item', 
            'customer_id', 
            'date', 
            'due_date', 
            'number', 
            'reference', 
            'discount', 
            'total', 
            'terms_and_conditions',
    ];



    public function customer()  {
        return $this->belongsTo(Customer::class);
    }
}

<?php namespace App\Models;

class Transaction extends BaseModel 
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'amount', 'interest', 'fee', 'date'];
    
    
}
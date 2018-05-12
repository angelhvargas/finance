<?php namespace App\Models;

class Bid extends BaseModel
{
    protected $table = 'bids';
    protected $primaryKey = 'id';
    protected $fillable = ['amount', 'placed_on', 'repayment_amount', 'outstanding_principal'];

    
}
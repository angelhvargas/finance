<?php namespace App\Models;

class Bid extends BaseModel
{
    protected $table = 'bids';
    protected $primaryKey = 'id';
    protected $fillable = ['amount', 'placed_on', 'repayment_amount', 'outstanding_principal'];

    public function lender()
    {
        return $this->belongsTo('App\Models\User', 'lender_id');
    }

    public function loan()
    {
        return $this->belongsTo('App\Models\Loan', 'loan_id');
    }

    
}
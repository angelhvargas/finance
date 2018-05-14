<?php namespace App\Models;

class Transaction extends BaseModel 
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'amount', 'interest', 'fee', 'date'];
    

    public function user() 
    {
        return $this->belongsTo('\App\Models\User', 'user_id');
    }

    public function bid() 
    {
        return $this->belongsTo('\App\Model\Bid', 'bid_id');
    }

    public function repayment()
    {
        return $this->belongsTo('\App\Model\Repayment');
    }
    
}
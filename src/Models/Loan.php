<?php namespace App\Models;
/**
 * Class Loan
 */
class Loan extends BaseModel 
{
    protected $table = "loans";
    protected $primaryKey = "id";
    protected $fillable = [
        "business", 
        "description", 
        "amount", 
        "term", 
        "grade", 
        "interest_rate", 
        "is_approved", 
        "approved_on", 
        "is_accepted", 
        "accepted_on"
    ];

    protected $hidden = ['borrower_id'];

    public function user() 
    {
        return $this->belongsTo("App\Models\User", "borrower_id", "id");
    }

    public function repayments()
    {
        return $this->hasMany("App\Models\Repayment", "loan_id");
    }

    public function bids()
    {
        return $this->hasMany("App\Models\Bid", "loan_id");
    }

}
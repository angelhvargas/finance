<?php namespace App\Models;

class User extends BaseModel 
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'first_name', 'last_name', 'email', 'date_of_birth'];
    protected $hidden = [];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }
    
    public function loans() 
    {
        return $this->hasMany('\App\Models\Loan', 'id', 'borrower_id');
    }

    public function bids() 
    {
        return $this->hasMany('\App\Models\Bid', 'lender_id');
    }

    public function repayments() 
    {
        return $this->hasManyThrough(
            'App\Models\Repayment',
            'App\Models\Loan',
            'borrower_id', // Foreign key on Loans table...
            'loan_id', // Foreign key on Repayments table...
            'id', // Local key on Users table...
            'id' // Local key on Loans table...
        );
    }

    public function transactions()
    {
        return $this->hasMany('\App\Models\Transaction', 'user_id');
    }

    public function lenders() 
    {
        return self::all()->where('type','=', 'lender')->toArray();
    }

    public function borrowers() 
    {
        return self::all()->where('type','=', 'borrower')->toArray();
    }


}
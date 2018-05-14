<?php namespace App\Models;

class User extends BaseModel 
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'first_name', 'last_name', 'email', 'date_of_birth'];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User')->withDefault();
    }
    
    public function loans() 
    {
        return $this->hasMany('\App\Model\Loan');
    }

    public function bids() 
    {
        return $this->hasMany('\App\Model\Bids');
    }

    public function withLoans($user_id) 
    {

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
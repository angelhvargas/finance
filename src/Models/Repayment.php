<?php namespace App\Models;

class Repayment extends BaseModel {

    protected $table = 'repayments';
    protected $primaryKey = 'id';

    public function loan()
    {
        return $this->belongsTo('\App\Models\Loan', 'loan_id');
    }
}
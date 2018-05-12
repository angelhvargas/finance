<?php namespace App\Models;

class User extends BaseModel 
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'first_name', 'last_name', 'email', 'date_of_birth'];
    



}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accounts extends Model
{
    use HasFactory;
    protected $table = 'accounts';
    
    protected $fillable = ['username', 'password', 'role', 'status', 'created_at'];
    protected $guarded = ['account_id'];
    protected $primaryKey = 'account_id';
    protected $hidden = ['password'];
    public $timestamps = false;

    public function employee()
    {
        return $this->hasOne(Employees::class, 'account_id', 'account_id');
 
    }
}
<?php

namespace RenokiCo\BillingPortal\Test\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use RenokiCo\CashierRegister\BillableWithStripe;

class User extends Authenticatable
{
    use BillableWithStripe;
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}

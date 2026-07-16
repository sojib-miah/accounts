<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function createdAccounts()
    {
        return $this->hasMany(Account::class, 'created_by');
    }

    public function updatedAccounts()
    {
        return $this->hasMany(Account::class, 'updated_by');
    }

    public function createdParties()
    {
        return $this->hasMany(Party::class, 'created_by');
    }

    public function updatedParties()
    {
        return $this->hasMany(Party::class, 'updated_by');
    }

    public function createdCategories()
    {
        return $this->hasMany(Category::class, 'created_by');
    }

    public function updatedCategories()
    {
        return $this->hasMany(Category::class, 'updated_by');
    }

    public function createdAccountHeads()
    {
        return $this->hasMany(AccountHead::class, 'created_by');
    }

    public function updatedAccountHeads()
    {
        return $this->hasMany(AccountHead::class, 'updated_by');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $fillable = [
        'name',
        'user_name',
        'email',
        'password',
        'role',
        'picture',
        'company_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the company that owns the user
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function profile() {
        return $this->hasOne(\App\Models\Profile::class, 'user_id', 'id');
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin()
    {
        return $this->role === 'super-admin';
    }

    /**
     * Check if user is company admin
     */
    public function isCompanyAdmin()
    {
        return $this->role === 'company-admin';
    }

    /**
     * Check if user can manage company
     */
    public function canManageCompany($companyId = null)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->isCompanyAdmin()) {
            return $companyId ? $this->company_id == $companyId : true;
        }

        return false;
    }
}

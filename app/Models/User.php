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
        'picture'
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
     * Check if user is admin for a specific company (formerly company-admin)
     */
    public function isCompanyAdmin($companyId)
    {
        return $this->roleForCompany($companyId) === 'admin';
    }

    /**
     * Check if user can manage company (super-admin or admin for that company)
     */
    public function canManageCompany($companyId)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->isCompanyAdmin($companyId);
    }

    /**
     * The companies this user belongs to.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_user')->withPivot('role')->withTimestamps();
    }

    /**
     * Get the user's role for a specific company.
     */
    public function roleForCompany($companyId)
    {
        $company = $this->companies->where('id', $companyId)->first();
        return $company ? $company->pivot->role : null;
    }
}

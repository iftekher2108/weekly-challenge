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
        return $this->hasOne(Profile::class, 'user_id', 'id');
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
    public function isCompanyAdmin($companyId = null)
    {
        // Super admins are considered admins for all companies
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($companyId === null) {
            // Get the first company the user belongs to as default
            $company = $this->companies()->first();
            if (!$company) {
                return false;
            }
            $companyId = $company->id;
        }
        return $this->roleForCompany($companyId) === 'admin';
    }

    /**
     * Check if user can manage company (super-admin or admin for that company)
     */
    public function canManageCompany($companyId = null)
    {
        // Super admins can manage all companies
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
    public function roleForCompany($companyId = null)
    {
        // Super admins have 'super-admin' role for all companies
        if ($this->isSuperAdmin()) {
            return 'super-admin';
        }

        if ($companyId === null) {
            // Get the first company the user belongs to as default
            $company = $this->companies()->first();
            if (!$company) {
                return null;
            }
            $companyId = $company->id;
        }
        $company = $this->companies->where('id', $companyId)->first();
        return $company ? $company->pivot->role : null;
    }

    /**
     * Get the primary company ID for this user
     */
    public function getPrimaryCompanyId()
    {
        // If user has a direct company_id, use that
        if ($this->company_id) {
            return $this->company_id;
        }

        // Otherwise, get the first company from the many-to-many relationship
        $company = $this->companies()->first();
        return $company ? $company->id : null;
    }
}

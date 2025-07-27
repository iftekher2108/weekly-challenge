<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'email',
        'phone',
        'address',
        'logo',
        'status'
    ];

    /**
     * The users that belong to this company.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user')->withPivot('role')->withTimestamps();
    }

    /**
     * Get admin users (formerly company-admins)
     */
    public function admins()
    {
        return $this->users()->wherePivot('role', 'admin');
    }

    /**
     * Get regular employees/users.
     */
    public function employees()
    {
        return $this->users()->wherePivotIn('role', ['user', 'editor', 'creator']);
    }

    /**
     * Check if company is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}

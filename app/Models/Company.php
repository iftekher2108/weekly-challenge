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
     * Get all users belonging to this company
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get company admin users
     */
    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'company-admin');
    }

    /**
     * Get regular employees/users
     */
    public function employees()
    {
        return $this->hasMany(User::class)->whereIn('role', ['user', 'editor', 'creator']);
    }

    /**
     * Check if company is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}

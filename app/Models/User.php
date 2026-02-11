<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Auditable;

class User extends Authenticatable
{
    use SoftDeletes, Auditable;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
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

    /**
     * Get the roles this user has.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Get all permissions through roles.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
            ->through('role_user');
    }

    /**
     * Get temporary permissions for this user.
     */
    public function temporaryPermissions()
    {
        return $this->hasMany(TemporaryPermission::class)->active();
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string|array $role): bool
    {
        if (is_array($role)) {
            return $this->roles()->whereIn('name', $role)->exists();
        }

        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Check if user has all the given roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->count() === count($roles);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string|array $permission): bool
    {
        if (is_array($permission)) {
            return $this->getAllPermissions()->whereIn('name', $permission)->count() === count($permission);
        }

        return $this->getAllPermissions()->where('name', $permission)->exists();
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->getAllPermissions()->whereIn('name', $permissions)->count() > 0;
    }

    /**
     * Get all permissions for the user from all roles and temporary permissions.
     */
    public function getAllPermissions()
    {
        $permissions = collect();

        // Get permissions from roles
        foreach ($this->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
        }

        // Add temporary permissions if they're still active
        foreach ($this->temporaryPermissions as $tempPerm) {
            if ($tempPerm->isValid()) {
                $permissions->push($tempPerm->permission);
            }
        }

        return $permissions->unique('id');
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole(Role|string $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        if (!$this->hasRole($role->name)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(Role|string $role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Remove all roles from the user.
     */
    public function removeAllRoles(): void
    {
        $this->roles()->detach();
    }

    /**
     * Sync roles for the user (replaces all existing roles).
     */
    public function syncRoles(array|string $roles): void
    {
        $roleIds = collect($roles)->map(function ($role) {
            return is_string($role) ? Role::where('name', $role)->firstOrFail()->id : $role->id;
        })->toArray();

        $this->roles()->sync($roleIds);
    }
}


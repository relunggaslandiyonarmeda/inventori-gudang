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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
        'profile_photo',
        'menu_permissions',
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
            'menu_permissions' => 'array',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user has access to a specific menu
     */
    public function hasMenuAccess(string $menu): bool
    {
        // Admin has full access
        if ($this->role === 'admin') {
            return true;
        }

        // Check menu_permissions array
        $permissions = $this->menu_permissions ?? [];
        return in_array($menu, $permissions);
    }

    /**
     * Get all allowed menus for user
     */
    public function getAllowedMenus(): array
    {
        if ($this->role === 'admin') {
            return ['master_barang', 'barang_masuk', 'barang_keluar', 'barang_retur', 'barang_rusak'];
        }
        return $this->menu_permissions ?? [];
    }

    /**
     * Check if user is user (non-admin)
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get the profile photo URL
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        if ($this->profile_photo) {
            return asset('storage/profile_photos/' . $this->profile_photo);
        }
        return null;
    }

    /**
     * Get default avatar (initials)
     */
    public function getAvatarAttribute(): string
    {
        $initials = strtoupper(substr($this->name, 0, 2));
        return $initials;
    }
}

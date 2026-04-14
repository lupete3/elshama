<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',    // Pour inventaire
        'name',
        'email',
        'password',
        'role_id',      // Pour inventaire
        'role',         // Pour boulangerie (string)
        'site_id',      // Pour boulangerie
        'is_active',
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
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function language()
    {
        return $this->hasOne(Language::class);
    }

    public function getLocaleAttribute()
    {
        return $this->language?->locale ?? config('app.locale');
    }

    /**
     * Détecte si l'utilisateur est de type Boulangerie
     */
    public function isBakeryUser(): bool
    {
        // Les SuperAdmins (role_id 4) ne sont jamais considérés comme des utilisateurs boulangerie standards
        if ($this->role_id == 4) {
            return false;
        }

        $bakeryRoles = ['admin', 'geran_depot_magasin', 'geran_depot_usine', 'geran_depot_boulangerie'];

        // On utilise getRawOriginal ou getAttribute('role') pour éviter le conflit avec la relation role()
        $roleValue = $this->getAttribute('role');

        if (is_string($roleValue) && in_array($roleValue, $bakeryRoles)) {
            return true;
        }

        // Cas particulier de l'utilisateur avec site_id mais sans tenant_id (caractéristique boulangerie)
        return is_null($this->tenant_id) && !is_null($this->site_id);
    }

    /**
     * Détecte si l'utilisateur est de type Inventaire
     */
    public function isInventoryUser(): bool
    {
        return !is_null($this->tenant_id);
    }

    /**
     * Relations pour app Boulangerie
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function inventaires()
    {
        return $this->hasMany(Cloture::class);
    }

    public function syntheses()
    {
        return $this->hasMany(Synthese::class);
    }

    /**
     * Méthode unifiée pour vérification de rôle
     * Compatible avec les deux systèmes
     */
    public function hasRoleString(string $role): bool
    {
        // Les SuperAdmins ont accès à tout par défaut dans les checks de rôle
        if ($this->role_id == 4) {
            return true;
        }

        if ($this->isBakeryUser()) {
            return $this->getAttribute('role') === $role;
        }

        // Pour inventaire - On utilise explicitement la relation pour vérifier le nom du rôle
        if ($this->role_id) {
            return $this->role()->first()?->name === $role;
        }

        return false;
    }

    /**
     * Accessor pour obtenir le nom du rôle de façon unifiée
     */
    public function getRoleNameAttribute(): string
    {
        if ($this->isBakeryUser()) {
            return $this->role ?? 'user';
        }

        return $this->role()->first()?->name ?? 'user';
    }

}

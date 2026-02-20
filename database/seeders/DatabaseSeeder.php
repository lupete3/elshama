<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\CompanySetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Créer ou mettre à jour les rôles
        $roles = [
            ['name' => 'Admin', 'description' => 'Admin d’un tenant (propriétaire de supermarché)'],
            ['name' => 'Gérant', 'description' => 'Gestion des ventes et produits'],
            ['name' => 'Caissier', 'description' => 'Gestion des ventes'],
            ['name' => 'Super Admin', 'description' => 'Gestion globale du SaaS'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }

        // 2️⃣ Créer un Plan par défaut si inexistant
        $plan = \App\Models\Plan::firstOrCreate(
            ['name' => 'PRO PLAN'],
            [
                'price' => 50000,
                'duration_days' => 30,
                'max_users' => 10,
                'max_stores' => 5,
            ]
        );

        // 3️⃣ Créer un Tenant (Entreprise) par défaut si inexistant
        $tenant = \App\Models\Tenant::firstOrCreate(
            ['email' => 'contact@pftecho.com'],
            [
                'name' => 'GES.BOULANGERIE',
                'contact_name' => 'Admin',
                'phone' => '0978222654',
                'address' => 'Bukavu, RDC',
                'is_active' => true,
            ]
        );

        // S'assurer que le tenant est actif
        $tenant->update(['is_active' => true]);

        // 4️⃣ Créer une Subscription active pour ce Tenant
        \App\Models\Subscription::updateOrCreate(
            ['tenant_id' => $tenant->id, 'is_active' => true],
            [
                'plan_id' => $plan->id,
                'amount' => $plan->price,
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(365)->toDateString(),
            ]
        );

        // 5️⃣ Créer ou mettre à jour le Super Admin
        $superAdminRole = \App\Models\Role::where('name', 'Super Admin')->first();
        User::updateOrCreate(
            ['email' => 'superadmin@pftecho.com'],
            [
                'tenant_id' => null,
                'role_id' => $superAdminRole->id,
                'name' => 'Super Admin SaaS',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // 6️⃣ Affecter les utilisateurs orphelins (qui ont un tenant_id nul mais ne sont pas boulangers) au tenant par défaut
        // Ou plus simplement, s'assurer que tous les utilisateurs existants qui devraient avoir un tenant en ont un valide
        User::whereNotNull('tenant_id')->where('tenant_id', 0)->update(['tenant_id' => $tenant->id]);

        // Si des utilisateurs n'ont pas de tenant_id mais ne sont pas isBakeryUser(), on peut les lier si besoin
        // Mais ici on va surtout s'occuper de ceux qui sont déjà typés "Inventaire"
        User::whereNull('tenant_id')->whereNull('site_id')->where('email', '!=', 'superadmin@pftecho.com')->update(['tenant_id' => $tenant->id]);

        // 7️⃣ Paramètres globaux
        CompanySetting::updateOrCreate(
            ['email' => 'contact@pftecho.com'],
            [
                'tenant_id' => null,
                'name' => 'GES.BOULANGERIE',
                'address' => 'Bukavu, RDC',
                'phone' => '0978222654',
                'rccm' => 'RC-RDC-0034A',
                'id_nat' => '7483945',
                'devise' => 'Fc',
            ]
        );
    }
}

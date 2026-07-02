<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'ghitanada'],
            [
                'name' => 'Ghita Nada',
                'email' => 'ghitanada@gds-dlimi.ma',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+212 6 00 00 00 00',
                'is_active' => true,
            ]
        );

        if (User::where('username', 'admin')->exists()) {
            return;
        }

        $admin = User::create([
            'name' => 'Administrateur GDS',
            'username' => 'admin',
            'email' => 'admin@gds-dlimi.ma',
            'password' => Hash::make('Gds@2024'),
            'role' => 'admin',
            'phone' => '+212 5 22 00 00 00',
            'is_active' => true,
        ]);

        $manager = User::create([
            'name' => 'Karim Benali',
            'username' => 'k.benali',
            'email' => 'k.benali@gds-dlimi.ma',
            'password' => Hash::make('Manager@2024'),
            'role' => 'manager',
            'phone' => '+212 6 12 34 56 78',
            'is_active' => true,
        ]);

        $technicians = collect([
            ['name' => 'Jean Dupont', 'username' => 'j.dupont', 'email' => 'j.dupont@gds-dlimi.ma'],
            ['name' => 'Ahmed El Amrani', 'username' => 'a.elamrani', 'email' => 'a.elamrani@gds-dlimi.ma'],
            ['name' => 'Youssef Idrissi', 'username' => 'y.idrissi', 'email' => 'y.idrissi@gds-dlimi.ma'],
        ])->map(fn ($data) => User::create([
            ...$data,
            'password' => Hash::make('Tech@2024'),
            'role' => 'technician',
            'is_active' => true,
        ]));

        $categories = collect([
            'Câblage & Connectique',
            'Équipements Réseau',
            'Serveurs & Stockage',
            'Outils & Consommables',
        ])->map(fn ($name) => Category::create(['name' => $name]));

        $products = [
            ['reference' => 'CAB-CAT6-305', 'name' => 'Câble Cat6 UTP 305m', 'category_id' => $categories[0]->id, 'quantity' => 12, 'min_quantity' => 3, 'unit' => 'bobine', 'location' => 'Rayon A-01', 'unit_price' => 850.00],
            ['reference' => 'SW-CIS-48P', 'name' => 'Switch Cisco 48 ports PoE', 'category_id' => $categories[1]->id, 'quantity' => 4, 'min_quantity' => 2, 'unit' => 'unité', 'location' => 'Rayon B-03', 'unit_price' => 12500.00],
            ['reference' => 'FO-SC-SM', 'name' => 'Connecteur fibre SC Single Mode', 'category_id' => $categories[0]->id, 'quantity' => 150, 'min_quantity' => 50, 'unit' => 'pièce', 'location' => 'Rayon A-05', 'unit_price' => 15.00],
            ['reference' => 'SRV-DELL-R740', 'name' => 'Serveur Dell PowerEdge R740', 'category_id' => $categories[2]->id, 'quantity' => 2, 'min_quantity' => 1, 'unit' => 'unité', 'location' => 'Rayon C-01', 'unit_price' => 45000.00],
            ['reference' => 'PDU-32A', 'name' => 'PDU 32A 24 ports', 'category_id' => $categories[1]->id, 'quantity' => 6, 'min_quantity' => 2, 'unit' => 'unité', 'location' => 'Rayon B-01', 'unit_price' => 3200.00],
            ['reference' => 'RJ45-CAT6', 'name' => 'Connecteur RJ45 Cat6', 'category_id' => $categories[0]->id, 'quantity' => 3, 'min_quantity' => 100, 'unit' => 'sachet', 'location' => 'Rayon A-02', 'unit_price' => 45.00],
            ['reference' => 'TEST-FLUK', 'name' => 'Testeur réseau Fluke', 'category_id' => $categories[3]->id, 'quantity' => 3, 'min_quantity' => 1, 'unit' => 'unité', 'location' => 'Atelier', 'unit_price' => 8900.00],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }

        Task::create([
            'title' => 'Installation baie réseau — Client Maroc Telecom',
            'description' => 'Installation et câblage d\'une baie 42U avec 2 switches Cisco 48 ports. Tests de continuité et certification Cat6.',
            'priority' => 'haute',
            'status' => 'en_cours',
            'assigned_to' => $technicians[0]->id,
            'created_by' => $manager->id,
            'client_name' => 'Maroc Telecom',
            'location' => 'Datacenter Casablanca',
            'due_date' => now()->addDays(3),
        ]);

        Task::create([
            'title' => 'Maintenance préventive serveurs',
            'description' => 'Vérification des serveurs Dell R740, nettoyage filtres, mise à jour firmware iDRAC.',
            'priority' => 'normale',
            'status' => 'en_attente',
            'assigned_to' => $technicians[1]->id,
            'created_by' => $manager->id,
            'client_name' => 'Banque Populaire',
            'location' => 'Site Rabat',
            'due_date' => now()->addDays(7),
        ]);

        Task::create([
            'title' => 'Tirage fibre optique inter-bâtiment',
            'description' => 'Tirage de 12 fibres SM entre bâtiment A et B. Soudure et tests OTDR.',
            'priority' => 'urgente',
            'status' => 'en_attente',
            'assigned_to' => $technicians[2]->id,
            'created_by' => $admin->id,
            'client_name' => 'OCP Group',
            'location' => 'Complexe Jorf Lasfar',
            'due_date' => now()->addDay(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Company;
use App\Models\Product;
use App\Models\Checksheet;
use Illuminate\Support\Str;

class ChecksheetProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Seed Companies
        $companies = [];
        for ($i = 0; $i < 5; $i++) {
            $companies[] = Company::create([
                'name' => $faker->company,
                'address' => $faker->address,
                'contact_info' => $faker->phoneNumber,
            ]);
        }

        // Seed Users
        $users = [];
        // Admins
        for ($i = 0; $i < 2; $i++) {
            $users[] = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'company_id' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }
        // QEs
        for ($i = 0; $i < 4; $i++) {
            $users[] = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'role' => 'qe',
                'company_id' => null,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }
        // Suppliers
        for ($i = 0; $i < 4; $i++) {
            $users[] = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'role' => 'supplier',
                'company_id' => $faker->randomElement($companies)->id,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }

        // Seed Products
        $products = [];
        foreach ($companies as $company) {
            for ($i = 0; $i < 4; $i++) {
                $products[] = Product::create([
                    'name' => $faker->word . ' ' . $faker->randomElement(['Widget', 'Gadget', 'Tool']),
                    'description' => $faker->sentence,
                    'company_id' => $company->id,
                ]);
            }
        }

        // Seed Checksheets
        $statuses = ['submitted', 'reviewed', 'approved', 'rejected'];
        for ($i = 0; $i < 30; $i++) {
            $product = $faker->randomElement($products);
            $supplier = $faker->randomElement(array_filter($users, fn($user) => $user->role === 'supplier'));
            $qe = $faker->randomElement(array_filter($users, fn($user) => $user->role === 'qe'));

            Checksheet::create([
                'product_id' => $product->id,
                'supplier_id' => $supplier->id,
                'qe_id' => $qe->id,
                'details' => json_encode([
                    'size' => $faker->randomElement(['Small', 'Medium', 'Large']),
                    'dimension' => $faker->numberBetween(10, 100) . 'x' . $faker->numberBetween(10, 100) . 'x' . $faker->numberBetween(10, 100),
                    'material' => $faker->randomElement(['Steel', 'Plastic', 'Wood']),
                ]),
                'document_path' => $faker->filePath(),
                'status' => $faker->randomElement($statuses),
                'submission_date' => $faker->dateTimeBetween('-1 year', 'now'),
                'warranty_expiry' => $faker->dateTimeBetween('now', '+2 years'),
            ]);
        }
    }
}
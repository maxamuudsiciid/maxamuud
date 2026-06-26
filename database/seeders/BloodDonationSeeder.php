<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Hospital;
use App\Models\Donor;
use Carbon\Carbon;

class BloodDonationSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@bloodbank.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Staff User
        User::create([
            'name' => 'Staff Member',
            'email' => 'staff@bloodbank.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Create Hospital
        $hospitalUser = User::create([
            'name' => 'City Hospital',
            'email' => 'contact@cityhospital.com',
            'password' => Hash::make('password'),
            'role' => 'hospital',
        ]);

        Hospital::create([
            'user_id' => $hospitalUser->id,
            'hospital_name' => 'City Hospital',
            'address' => '123 Main St, Springfield',
            'phone' => '123-456-7890',
            'email' => 'contact@cityhospital.com',
            'contact_person' => 'Dr. Smith',
        ]);

        // Create Donor
        $donorUser = User::create([
            'name' => 'John Donor',
            'email' => 'john@donor.com',
            'password' => Hash::make('password'),
            'role' => 'donor',
        ]);

        Donor::create([
            'user_id' => $donorUser->id,
            'full_name' => 'John Donor',
            'gender' => 'Male',
            'date_of_birth' => '1990-01-01',
            'blood_group' => 'O+',
            'phone' => '098-765-4321',
            'email' => 'john@donor.com',
            'address' => '456 Donor Ave',
            'last_donation_date' => Carbon::now()->subMonths(4),
            'status' => 'Active',
        ]);
        
        // Let's add some initial inventory
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach($bloodGroups as $bg) {
            \App\Models\BloodInventory::create([
                'blood_group' => $bg,
                'quantity' => rand(500, 2000), // Random starting quantity in ml
            ]);
        }

        // Add dummy Blood Collection
        $donor = Donor::first();
        if ($donor) {
            \App\Models\BloodCollection::create([
                'donor_id' => $donor->id,
                'blood_group' => $donor->blood_group,
                'quantity' => 450,
                'donation_date' => Carbon::now()->subDays(5),
                'expiry_date' => Carbon::now()->subDays(5)->addDays(42),
                'screening_result' => 'Pending',
            ]);
        }

        // Add dummy Blood Request
        $hospital = Hospital::first();
        if ($hospital) {
            \App\Models\BloodRequest::create([
                'hospital_id' => $hospital->id,
                'patient_name' => 'Jane Smith',
                'blood_group' => 'A+',
                'quantity' => 1000,
                'request_date' => Carbon::now(),
                'status' => 'Pending',
                'urgency_level' => 'Urgent',
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'company_name' => 'Bill-it',
            'company_address' => '123 Main Street, Cityville',
            'company_phone_no' => '123-456-7890',
            'company_email' => 'info@bill-it.com',
            'company_pan' => 'ABCDE1234F',
            'company_registration_no' => 'REG123456',
            'company_website' => 'billit.arnavstha.com.np',
            'currency' => 'NPR',
        ]);
    }
}

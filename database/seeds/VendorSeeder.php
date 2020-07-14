<?php

use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addVendors();
    }

    /**
     * Create Super Admins
     */
    private function addVendors()
    {
        App\Models\Vendor::create([
            'name' => 'Hotel 1',
            'email' => 'hotel@example.com',
            'mobile' => '1234567890',
            'password' => bcrypt('123456'),
            'address' => 'tested address',
            'vendor_category' => 1,
            'lat'=>'00',
            'long'=>'00',
            'qr_code_path'=>'sadas',
        ]);

        App\Models\Vendor::create([
            'name' => 'Hotel 2',
            'email' => 'vendor2@example.com',
            'mobile' => '1234567809',
            'password' => bcrypt('123456'),
            'address' => 'tested address',
            'vendor_category' => 1,
            'lat'=>'00',
            'long'=>'00',
            'qr_code_path'=>'sadas',
        ]);
    }
}

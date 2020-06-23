<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addAdmins();
    }

    /**
     * Create Super Admins
     */
    private function addAdmins()
    {
        App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'mobile' => '1234567890',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'active' => true,
        ]);

        App\Models\User::create([
            'name' => 'Ultra Admin',
            'email' => 'admin2@example.com',
            'mobile' => '1234567890',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'active' => true,
        ]);
    }


}

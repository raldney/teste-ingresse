<?php

use Illuminate\Database\Seeder;
use App\User;

use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdmin();
    }

    protected function createAdmin()
    {
        if ( ! $this->adminExists() ) {
            User::create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin')
            ]);
        }
    }

    protected function adminExists()
    {
        return User::where('email','=','admin@admin.com')->exists();
    }
}
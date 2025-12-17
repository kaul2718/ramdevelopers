<?php
namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Admin',
            'lastname'     => 'Urbano',
            'email'    => 'admin@ramdevelopers.com',
            'password' => Hash::make('admin'),
        ]);

        $admin->assignRole('admin');

        $editor = User::create([
            'name'     => 'Editor',
            'lastname'     => 'Urbano',
            'email'    => 'editor@ramdevelopers.com',
            'password' => Hash::make('editor'),
        ]);
        $editor->assignRole('editor');

    }
}

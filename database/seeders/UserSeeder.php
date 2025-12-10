<?php
namespace Database\Seeders;

use Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin', 
            'email' => 'admin@ramdevelopers.com', 
            'password' => Hash::make('admin')
        ]);

        $admin->assignRole('admin');

         $editor = User::create([
            'name' => 'editor', 
            'email' => 'editor@ramdevelopers.com', 
            'password' => Hash::make('editor')
        ]);
        $editor->assignRole('editor');

    }
}

<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = [
            [
                'id' => 1,
                'name' => 'Raziyeh',
                'email' => 'mrs.safari20@gmail.com',
                'password' => bcrypt('123456'),
                'role' => 'admin',
            ],
            [
                'id' => 2,
                'name' => 'Moein',
                'email' => 'm.pakkhesal@alopeyk.com',
                'password' => bcrypt('123456'),
                'role' => 'admin',
            ],
        ];
        foreach($users as $user){
            User::updateOrCreate(['id' => $user['id']], $user);
        }
    }
}

<?php

use App\Book_Text;
use App\User;
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
        /* User::create([
             'first_name' => 'Komal',
             'last_name' => 'savla',
             'email' => 'komal.savla@focalworks.in',
             'password' => bcrypt('password'),
         ])->assignRole('super-admin');*/

        /*User::create([
            'first_name' => 'Abhijeet',
            'last_name' => 'Bhujpal',
            'email' => 'abhijeet.bhujbal@aptaracorp.com',
            'password' => bcrypt('6cq0pt'),
        ])->assignRole('editor');*/

        User::create([
            'first_name' => 'Mike',
            'last_name' => 'Molnar',
            'email' => 'asme.mike.molnar@gmail.com',
            'password' => bcrypt('asmeulemfm2020'),
        ])->assignRole('editor');

        /*  User::create([
             'first_name' => 'Contributor',
             'last_name' => 'Contributor',
             'email' => 'kom.savla@gmail.com',
             'password' => bcrypt('password'),
         ])->assignRole('contributor');

        User::create([
             'first_name' => 'Abhijeet',
             'last_name' => 'Satpute',
             'email' => 'abhijeet.satpute@focalworks.in',
             'password' => bcrypt('password'),
         ])->assignRole('contributor');*/

        Book_Text::create([
            'text' => 'Editor instructions--he will insert here.'
        ]);

    }
}

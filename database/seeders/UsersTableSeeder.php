<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 13029781151,
                'username' => 'developpeur',
                'phone' => '0142855584',
                'email' => 'developpeur@gmail.com',
                'email_verified_at' => NULL,
                'password' => '$2y$12$no.XDNkLCKha/1ZSDv0bhu3pJScaf5m727/wLa1VQS/XRcAp7En8.',
                'avatar' => NULL,
                'role' => 'developpeur',
                'remember_token' => NULL,
                'created_at' => '2025-04-22 11:16:21',
                'updated_at' => '2025-04-22 18:00:33',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
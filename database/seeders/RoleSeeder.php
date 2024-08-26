<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
/*
*
Run the database seeds.*
@return void
*/
public function run(){
    // Role data
    

        // Insert role data into the roles table
        DB::table('roles')->insert(["role"=> "user"]);
        DB::table('roles')->insert(["role"=> "lessor"]);
        DB::table('roles')->insert(["role"=> "admin"]);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert(
            [
               'id'          =>'1',
               'username'    =>'admin',
               'password'    =>bcrypt('password'),
               'role'        =>'2',
               'status'      =>'0',
               'created_at'  =>date('Y:m:d H:i:s'),
               'created_by'  =>1,
               'updated_at'  =>date('Y:m:d H:i:s'),
               'updated_by'  =>1,

            ]
            );
            DB::table('users')->insert(
                [
                   'id'          =>'2',
                   'username'    =>'001',
                   'password'    =>bcrypt('111111'),
                   'role'        =>'3',
                   'status'      =>'0',
                   'created_at'  =>date('Y:m:d H:i:s'),
                   'created_by'  =>1,
                   'updated_at'  =>date('Y:m:d H:i:s'),
                   'updated_by'  =>1,

                ]
                );
    }
}

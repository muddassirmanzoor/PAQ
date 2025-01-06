<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDistrict;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('users')->truncate();

        $user = User::create([
            'department_id' => '1',
            'designation' => 'DEO',
            'user_name' => 'deo1',
            'full_name' => 'Data Entry operator',
            'email' => 'deo@sed.com',
            'password' => bcrypt('deo123'),
            'user_contact' => '923213213215',
            'security_answer' => 'qaz',
        ]);
        $user->assignRole('DEO');

        $user = User::create([
            'department_id' => '1',
            'designation' => 'SO-AB',
            'user_name' => 'so-ab1',
            'full_name' => 'Section Officer',
            'email' => 'so.ab@sed.com',
            'password' => bcrypt('so123'),
            'user_contact' => '923213213215',
            'security_answer' => 'zaq',
        ]);
        $user->assignRole('SO-AB');

        $user = User::create([
            'department_id' => '1',
            'designation' => 'LIAISON-OFFICER',
            'user_name' => 'lo',
            'full_name' => 'Lison Officer',
            'email' => 'lo@sed.com',
            'password' => bcrypt('lo123'),
            'user_contact' => '923213213215',
            'security_answer' => 'zaq',
        ]);
        $user->assignRole('LIAISON-OFFICER');



        $departments = [
            'CEO-DEA', 'PMIU', 'QAED', 'PCTB', 'PEF', 'PEIMA',
            'PEC', 'PTF', 'NMST', 'DAANISH AUTHORITY', 'CLC'
        ];

        foreach ($departments as $index => $department) {
            for ($i = 1; $i <= 2; $i++) {
                $userName = strtolower(str_replace(' ', '-', $department)) . $i;
                $fullName = $department . ' User ' . $i;
                $email = $userName . '@sed.com';
                $password = bcrypt($userName . '123');
                $userContact = '923213213215';
                $securityAnswer = 'zaq';

                if (!User::where('user_name', $userName)->exists()) {
                    $user = User::create([
                        'department_id' => $index + 2, // +2 because index starts from 0 and 'SED' is department_id 1
                        'designation' => $department,
                        'user_name' => $userName,
                        'full_name' => $fullName,
                        'email' => $email,
                        'password' => $password,
                        'user_contact' => $userContact,
                        'security_answer' => $securityAnswer,
                    ]);
                    $user->assignRole($department);
                }
            }
        }

        // Super Admin and Admin users
        $specialUsers = [
            [
                'department_id' => '0',
                'designation' => 'Super Admin',
                'user_name' => 'superadmin',
                'full_name' => 'Super Admin',
                'email' => 'superadmin@sed.com',
                'password' => bcrypt('superadmin123'),
                'user_contact' => '923213213215',
                'security_answer' => 'zaq',
            ],
            [
                'department_id' => '0',
                'designation' => 'Admin',
                'user_name' => 'admin',
                'full_name' => 'Admin',
                'email' => 'admin@sed.com',
                'password' => bcrypt('admin123'),
                'user_contact' => '923213213215',
                'security_answer' => 'zaq',
            ],
            [
                'department_id' => '1',
                'designation' => 'Assembly',
                'user_name' => 'Assembly',
                'full_name' => 'Assembly',
                'email' => 'assembly@sed.com',
                'password' => bcrypt('assembly123'),
                'user_contact' => '923213213215',
                'security_answer' => 'zaq',
            ],
            [
                'department_id' => '1',
                'designation' => 'Minister',
                'user_name' => 'Minister',
                'full_name' => 'Minister',
                'email' => 'minister@sed.com',
                'password' => bcrypt('minister123'),
                'user_contact' => '923213213215',
                'security_answer' => 'zaq',
            ],
            [
                'department_id' => '1',
                'designation' => 'AS',
                'user_name' => 'AS',
                'full_name' => 'Additional Sectary',
                'email' => 'as@sed.com',
                'password' => bcrypt('as123'),
                'user_contact' => '923213213215',
                'security_answer' => 'zaq',
            ],
            [
                'department_id' => '1',
                'designation' => 'DS',
                'user_name' => 'DS',
                'full_name' => 'Deputy Sectary',
                'email' => 'ds@sed.com',
                'password' => bcrypt('ds123'),
                'user_contact' => '923213213215',
                'security_answer' => 'zaq',
            ],
            [
                'department_id' => '1',
                'designation' => 'SS',
                'user_name' => 'SS',
                'full_name' => 'Special Sectary',
                'email' => 'ss@sed.com',
                'password' => bcrypt('ds123'),
                'user_contact' => '923213213215',
                'security_answer' => 'zaq',
            ],
            [
                'department_id' => '0',
                'designation' => 'Secretary',
                'user_name' => 'Secretary',
                'full_name' => 'Secretary',
                'email' => 'sectary@sed.com',
                'password' => bcrypt('sectary123'),
                'user_contact' => '923213213215',
                'security_answer' => 'zaq',
            ],
        ];

        foreach ($specialUsers as $userData) {
            // Check if the user already exists
            if (!User::where('user_name', $userData['user_name'])->exists()) {
                $user = User::create($userData);
                $user->assignRole($userData['designation']);
            }
        }

//        $user = User::create([
//            'department_id' => '1',
//            'designation' => 'DEO',
//            'user_name' => 'deo1',
//            'full_name' => 'Data Entry operator',
//            'email' => 'deo@sed.com',
//            'password' => bcrypt('deo123'),
//            'user_contact' => '923213213215',
//            'security_answer' => 'qaz',
//        ]);
//        $user->assignRole('DEO');
//
//        $user = User::create([
//            'department_id' => '1',
//            'designation' => 'SO-AB',
//            'user_name' => 'so-ab1',
//            'full_name' => 'Section Officer',
//            'email' => 'so.ab@sed.com',
//            'password' => bcrypt('so123'),
//            'user_contact' => '923213213215',
//            'security_answer' => 'zaq',
//        ]);
//        $user->assignRole('SO-AB');
//
//        $user = User::create([
//            'department_id' => '1',
//            'designation' => 'SO-AB',
//            'user_name' => 'lo',
//            'full_name' => 'Lison Officer',
//            'email' => 'lo@sed.com',
//            'password' => bcrypt('lo123'),
//            'user_contact' => '923213213215',
//            'security_answer' => 'zaq',
//        ]);
//        $user->assignRole('LIAISON-OFFICER');
//
//        $user = User::create([
//            'department_id' => '1',
//            'designation' => 'Sectary',
//            'user_name' => 'sec',
//            'full_name' => 'Sectary',
//            'email' => 'sectary@sed.com',
//            'password' => bcrypt('sectary123'),
//            'user_contact' => '923213213215',
//            'security_answer' => 'zaq',
//        ]);
//        $user->assignRole('SED');
//
//        $user = User::create([
//            'department_id' => '1',
//            'designation' => 'Admin',
//            'user_name' => 'admin',
//            'full_name' => 'Admin',
//            'email' => 'admin@sed.com',
//            'password' => bcrypt('admin123'),
//            'user_contact' => '923213213215',
//            'security_answer' => 'zaq',
//        ]);
//        $user->assignRole('Admin');
//
//        $user = User::create([
//            'department_id' => '0',
//            'designation' => 'Super Admin',
//            'user_name' => 'Super Admin',
//            'full_name' => 'Super Admin',
//            'email' => 'sadmin@sed.com',
//            'password' => bcrypt('sadmin123'),
//            'user_contact' => '923213213215',
//            'security_answer' => 'zaq',
//        ]);
//        $user->assignRole('Super Admin');
    }
}

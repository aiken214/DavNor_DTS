<?php

namespace Database\Seeders;

//use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\DtsSystemSetting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionsRoleTableSeeder::class,
            DtsSectionCategoriesTableSeeder::class,
            OfficeTableSeeder::class,
            DtsSectionTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            DtsOrganizationsTableSeeder::class,
            DtsSystemSettingTableSeeder::class,
            DtsDocTypeTableSeeder::class,
            DtsRouteStatusTableSeeder::class,           
            DtsInOutTableSeeder::class,
            DocStatusTableSeeder::class,
           
           
        ]);
    }
}

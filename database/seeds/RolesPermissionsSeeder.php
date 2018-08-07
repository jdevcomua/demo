<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'Адміністратор системи';
        $admin->save();

//        $role = new Role();
//        $role->name         = 'content-admin';
//        $role->display_name = 'Адміністратор контенту';
//        $role->save();

        $recorder = new Role();
        $recorder->name         = 'recorder';
        $recorder->display_name = 'Реєстратор';
        $recorder->save();

//        $role = new Role();
//        $role->name         = 'owner';
//        $role->display_name = 'Власник';
//        $role->save();

        $adminPanel = new Permission();
        $adminPanel->name         = 'admin-panel';
        $adminPanel->display_name = 'Доступ до адмін-панелі';
        $adminPanel->save();

        $verifyAnimal = new Permission();
        $verifyAnimal->name         = 'verify-animal';
        $verifyAnimal->display_name = 'Верифікація тварин';
        $verifyAnimal->save();

        $changeRoles = new Permission();
        $changeRoles->name          = 'change-roles';
        $changeRoles->display_name  = 'Змінювати ролі користувачів';
        $changeRoles->save();

        $blockUser = new Permission();
        $blockUser->name             = 'block-user';
        $blockUser->display_name     = 'Заблокувати користувача';
        $blockUser->save();


        $admin->attachPermissions([
            $adminPanel,
            $changeRoles,
            $blockUser
        ]);

        $recorder->attachPermission($adminPanel);
        $recorder->attachPermission($verifyAnimal);
    }
}

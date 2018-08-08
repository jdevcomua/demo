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

        $contentAdmin = new Role();
        $contentAdmin->name         = 'content-admin';
        $contentAdmin->display_name = 'Адміністратор контенту';
        $contentAdmin->save();

        $recorder = new Role();
        $recorder->name         = 'recorder';
        $recorder->display_name = 'Реєстратор';
        $recorder->save();


        $adminPanel = new Permission();
        $adminPanel->name         = 'admin-panel';
        $adminPanel->display_name = 'Доступ до адмін-панелі';
        $adminPanel->save();

        $verifyAnimal = new Permission();
        $verifyAnimal->name         = 'verify-animal';
        $verifyAnimal->display_name = 'Верифікація тварин';
        $verifyAnimal->save();

        $deleteAnimal = new Permission();
        $deleteAnimal->name             = 'delete-animal';
        $deleteAnimal->display_name     = 'Видалення тварин';
        $deleteAnimal->save();

        $editContent = new Permission();
        $editContent->name          = 'edit-content';
        $editContent->display_name  = 'Редагування контенту';
        $editContent->save();

        $changeRoles = new Permission();
        $changeRoles->name          = 'change-roles';
        $changeRoles->display_name  = 'Зміна ролей користувачів';
        $changeRoles->save();

        $editRoles = new Permission();
        $editRoles->name          = 'edit-roles';
        $editRoles->display_name  = 'Редагування дозволів ролей';
        $editRoles->save();

        $blockUser = new Permission();
        $blockUser->name             = 'block-user';
        $blockUser->display_name     = 'Блокування користувачів';
        $blockUser->save();

        $deleteUser = new Permission();
        $deleteUser->name             = 'delete-user';
        $deleteUser->display_name     = 'Видалення користувачів';
        $deleteUser->save();

        $viewSyslog = new Permission();
        $viewSyslog->name             = 'view-syslog';
        $viewSyslog->display_name     = 'Перегляд системного журналу';
        $viewSyslog->save();


        $admin->attachPermissions([
            $adminPanel,
            $verifyAnimal,
            $deleteAnimal,
            $editContent,
            $changeRoles,
            $editRoles,
            $blockUser,
            $deleteUser,
            $viewSyslog
        ]);

        $contentAdmin->attachPermissions([
            $adminPanel,
            $editContent,
        ]);

        $recorder->attachPermissions([
            $adminPanel,
            $verifyAnimal,
        ]);

    }
}

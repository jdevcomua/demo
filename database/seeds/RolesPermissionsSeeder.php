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
        $adminPanel = $this->addPermission('Доступ до адмін-панелі', 'admin-panel');
        $verifyAnimal = $this->addPermission('Верифікація тварин', 'verify-animal');
        $deleteAnimal = $this->addPermission('Видалення тварин', 'delete-animal');
        $editContent = $this->addPermission('Редагування контенту', 'edit-content');
        $changeRoles = $this->addPermission('Зміна ролей користувачів', 'change-roles');
        $privateData = $this->addPermission('Перегляд персональних данних користувачів', 'private-data');
        $blockUser = $this->addPermission('Блокування користувачів', 'block-user');
        $deleteUser = $this->addPermission('Видалення користувачів', 'delete-user');
        $editUser = $this->addPermission('Редагування користувачів', 'edit-user');
        $editRoles = $this->addPermission('Редагування дозволів ролей', 'edit-roles');
        $viewSyslog = $this->addPermission('Перегляд системного журналу', 'view-syslog');
        $editOrganizations = $this->addPermission('Управління закладами та установами', 'edit-organizations');
        $viewAnimals = $this->addPermission('Перегляд тварин', 'view-animals');
        $editAnimals = $this->addPermission('Редагування тварин', 'edit-animals');
        $createAnimals = $this->addPermission('Створення тварин', 'create-animals');
        $viewUsers = $this->addPermission('Перегляд користувачів', 'view-users');


        $this->syncPermissions(
            $this->addRole('Адміністратор системи', 'admin'),
            [
                $adminPanel,
                $verifyAnimal,
                $deleteAnimal,
                $editContent,
                $changeRoles,
                $editRoles,
                $blockUser,
                $editUser,
                $deleteUser,
                $viewSyslog,
                $privateData,
                $editOrganizations,
                $viewAnimals,
                $editAnimals,
                $createAnimals,
                $viewUsers
            ]
        );

        $this->syncPermissions(
            $this->addRole('Адміністратор контенту', 'content-admin'),
            [
                $adminPanel,
                $editContent,
            ]
        );

        $this->syncPermissions(
            $this->addRole('Реєстратор', 'recorder'),
            [
                $adminPanel,
                $verifyAnimal,
            ]
        );

        \Cache::tags(config('entrust.role_user_table'))->flush();
        \Cache::tags(config('entrust.roles_table'))->flush();
        \Cache::tags(config('entrust.permission_role_table'))->flush();
        \Cache::tags(config('entrust.permission'))->flush();
    }

    /**
     * @param $display
     * @param $name
     * @return Permission
     */
    private function addPermission($display, $name)
    {
        return $this->addItem(new Permission(), $display, $name);
    }

    /**
     * @param $display
     * @param $name
     * @return Role
     */
    private function addRole($display, $name)
    {
        return $this->addItem(new Role(), $display, $name);
    }

    private function addItem($model, $display, $name)
    {
        if (!$res = $model->where('name', '=', $name)->first()) {
            $res = $model->create([
                'name' => $name,
                'display_name' => $display
            ]);
        }
        return $res;
    }

    /**
     * @param Role $role
     * @param array $permissions
     */
    private function syncPermissions($role, $permissions)
    {
        $permissions = (new \Illuminate\Support\Collection($permissions))
            ->pluck('id')
            ->toArray();

        $role->perms()->sync($permissions);
    }
}

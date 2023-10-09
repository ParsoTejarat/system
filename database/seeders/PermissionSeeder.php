<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
//            'users-list' => 'لیست کاربران',
//            'users-create' => 'ایجاد کاربر',
//            'users-edit' => 'ویرایش کاربر',
//            'users-delete' => 'حذف کاربر',
//
//            'roles-list' => 'لیست نقش ها',
//            'roles-create' => 'ایجاد نقش',
//            'roles-edit' => 'ویرایش نقش',
//            'roles-delete' => 'حذف نقش',
//
//            'categories-list' => 'لیست دسته بندی ها',
//            'categories-create' => 'ایجاد دسته بندی',
//            'categories-edit' => 'ویرایش دسته بندی',
//            'categories-delete' => 'حذف دسته بندی',
//
//            'products-list' => 'لیست محصولات',
//            'products-create' => 'ایجاد محصول',
//            'products-edit' => 'ویرایش محصول',
//            'products-delete' => 'حذف محصول',
//
//            'printers-list' => 'لیست پرینتر ها',
//            'printers-create' => 'ایجاد پرینتر',
//            'printers-edit' => 'ویرایش پرینتر',
//            'printers-delete' => 'حذف پرینتر',
        ];

        foreach ($items as $key => $item)
        {
            Permission::create([
                'name' => $key,
                'label' => $item,
            ]);
        }
    }
}

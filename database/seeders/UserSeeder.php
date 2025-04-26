<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $userData =   [
            [
                'name' => 'tiklatamircigelsin', // Replace with desired name
                'email' => 'admin@tiklatamircigelsin.com', // Replace with desired email
                'phone' => '+201113051656', // Replace with desired phone number
                'address' => '123 Main St, Anytown, USA', // Replace with desired address
                'age' => '21', // Replace with desired age
                'password' => bcrypt('12345678'), // Replace with desired password
                'type' => 'admin', // Change as needed
                'login_type' => 'normal', // Change as needed
                'image' => 'imagesfp/setting/a.png', // Replace with desired image path
                'fcm' => 'some-fcm-token', // Replace with desired FCM token
                'code' => 'unique-code', // Replace with desired unique code
                'lat' => '37.7749', // Replace with desired latitude
                'long' => '-122.4194', // Replace with desired longitude
                'status' => true,
                'invitation_code' => 'invitation-code', // Replace with desired invitation code
                'email_verified_at' => now(),
                'country_id' => 1, // Example country_id, change as needed
                'city_id' => 1,    // Example city_id, change as needed
                'category_id' => null, // Or set a valid ID if needed
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Insert sample regular user                                                                                                                                                                                                              s
            [
                'name' => 'Taha khaled', // Replace with desired name
                'email' => 'tahakhaled419@gmail.com', // Replace with desired email
                'phone' => '+201113051685', // Replace with desired phone number
                'address' => '123 Main St, Anytown, USA', // Replace with desired address
                'age' => '21', // Replace with desired age
                'password' => bcrypt('12345678'), // Replace with desired password
                'type' => 'user', // Change as needed
                'login_type' => 'normal', // Change as needed
                'image' => 'imagesfp/setting/a.png', // Replace with desired image path
                'fcm' => 'some-fcm-token', // Replace with desired FCM token
                'code' => 'unique-code', // Replace with desired unique code
                'lat' => '37.7749', // Replace with desired latitude
                'long' => '-122.4194', // Replace with desired longitude
                'status' => true,
                'invitation_code' => 'invitation-code', // Replace with desired invitation code
                'email_verified_at' => now(),
                'country_id' => 1, // Example country_id, change as needed
                'city_id' => 1,    // Example city_id, change as needed
                'category_id' => null, // Or set a valid ID if needed
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'mohaned mohamed', // Replace with desired name
                'email' => 'mohaned@gmail.com', // Replace with desired email
                'phone' => '+201113051597', // Replace with desired phone number
                'address' => '123 Main St, Anytown, USA', // Replace with desired address
                'age' => '21', // Replace with desired age
                'password' => bcrypt('12345678'), // Replace with desired password
                'type' => 'user', // Change as needed
                'login_type' => 'normal', // Change as needed
                'image' => 'imagesfp/setting/a.png', // Replace with desired image path
                'fcm' => 'some-fcm-token', // Replace with desired FCM token
                'code' => 'unique-code', // Replace with desired unique code
                'lat' => '37.7749', // Replace with desired latitude
                'long' => '-122.4194', // Replace with desired longitude
                'status' => true,
                'invitation_code' => 'invitation-code', // Replace with desired invitation code
                'email_verified_at' => now(),
                'country_id' => 1, // Example country_id, change as needed
                'city_id' => 1,    // Example city_id, change as needed
                'category_id' => null, // Or set a valid ID if needed
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'mohamed Yousif', // Replace with desired name
                'email' => 'tahakhaled420@gmail.com', // Replace with desired email
                'phone' => '+201113032650', // Replace with desired phone number
                'address' => '123 Main St, Anytown, USA', // Replace with desired address
                'age' => '21', // Replace with desired age
                'password' => bcrypt('12345678'), // Replace with desired password
                'type' => 'vendor', // Change as needed
                'login_type' => 'normal', // Change as needed
                'image' => 'imagesfp/setting/a.png', // Replace with desired image path
                'fcm' => 'some-fcm-token', // Replace with desired FCM token
                'code' => 'unique-code', // Replace with desired unique code
                'lat' => '37.7749', // Replace with desired latitude
                'long' => '-122.4194', // Replace with desired longitude
                'status' => true,
                'invitation_code' => 'invitation-code', // Replace with desired invitation code
                'email_verified_at' => now(),
                'country_id' => 1, // Example country_id, change as needed
                'city_id' => 1,    // Example city_id, change as needed
                'category_id' => 1, // Or set a valid ID if needed
                'remember_token' => "some-remember-token",
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];


        $userPermission = [
            'admin',
            'vendor',
            'user',
        ];
        $PermissionAdmin = [
            'الصفحه الرئيسيه',
            'الصفحه الرئيسيه للتاجر',
            'عام',
            'الاقسام',
            'جميع الاقسام',
            'البنرات الإعلانية',
            'اضافة قسم',
            'تعديل قسم',
            'حذف قسم',
            'الاقسام الفرعيه',
            'جميع الاقسام الفرعيه',
            'اضافة الاقسام الفرعيه',
            'حذف الاقسام الفرعيه',
            'تعديل الاقسام الفرعيه',
            'تسوق',
            'المنتجات',
            'جميع المنتجات',
            'المنتجات الغير مفعله',
            'اضافة منتج',
            'تعديل منتج',
            'حذف منتج',
            'حالة منتج',
            'نسخ المنتج',
            'الالوان و الاحجام',
            'بوبات الدفع',
            'الالوان',
            'اضافة لون',
            'تعديل لون',
            'حذف لون',
            'الاحجام',
            'اضافة حجم',
            'تعديل حجم',
            'حذف حجم',
            'القسائم',
            'جميع القسائم',
            'اضافة قسيمه',
            'تعديل قسيمه',
            'حذف قسيمه',
            'اعدادت الهدايا',
            'الطلبيات',
            'جميع الطلبيات',
            'عرض الطلبيه',
            'حذف الطلبيه',
            'طباعة الطلبيه',
            'شكاوي المستخدمين',
            'المستخدمين',
            'رؤية المستخدمين',
            'صلاحيات المستخدمين',
            'الدول و الضرائب',
            // 'رؤية الدول',
            // 'رؤية المدن',
            // 'الابلاغات',
            'التقارير و الاستعلامات',
            'الاعدادات',
            'اعدادت الصفحات',
            'الاعدادت الرئيسيه',
            'الاعدادت العامه',
            'الصفحه الرئيسيه للبائع',
            'المتتجات الخاصه',


        ];

        $Permissionvendor = [
            'الصفحه الرئيسيه للبائع',
            'الصفحه الرئيسيه للتاجر',
            'المتتجات الخاصه',
            'الاعدادات',
            'الاعدادت العامه',
            'الاعدادت الرئيسيه',
            'المنتجات',
            'اضافة منتج',
            'تعديل منتج',
            'حذف منتج',
            'تسوق',
        ];


        $roleList = [];
        foreach ($userPermission as $permissionName) {
            $role = Role::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);

            if ($role->name == 'admin') {
                $role->syncPermissions($PermissionAdmin);
            } else {
                $role->syncPermissions($Permissionvendor);
            }

            $roleList[] = $role->id;
        }

        foreach ($userData as $data) {
            $user = User::create($data);
            if ($user->id == 1) {
                $user->assignRole([$roleList[0]]);
            } elseif ($user->id == 2) {
                $user->assignRole([$roleList[1]]);
            } else {
                $user->assignRole([$roleList[2]]);
            }
        }


        $faker = Faker::create();
        for ($i = 0; $i < 15; $i++) { // You can change 10 to whatever number of users you want to seed
            FacadesDB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'age' => $faker->numberBetween(18, 65),
                'password' => bcrypt('password'), // Change this as needed, you might want to hash a default password
                'type' => $faker->randomElement(['user', 'vendor', 'admin']),
                'login_type' => $faker->randomElement(['google', 'apple', 'facebook', 'normal']),
                'image' => 'imagesfp/setting/a.png',
                'fcm' => Str::random(30),
                'code' => Str::random(6),
                'lat' => $faker->latitude,
                'long' => $faker->longitude,
                'status' => $faker->boolean,
                'invitation_code' => Str::random(8),
                'email_verified_at' => now(),
                'country_id' => $faker->numberBetween(1, 1), // Assuming country IDs are from 1 to 10
                'city_id' => $faker->numberBetween(1, 2), // Assuming city IDs are from 1 to 10
                'category_id' => $faker->numberBetween(1, 2), // Optional category
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Enums\Gates\RolesEnum;
use App\Models\AddressUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Random\RandomException;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws RandomException
     */
    public function run(): void
    {
        $user = User::create([
            'username' => '09179516271',
            'password' => Hash::make('m9516271'),
            'first_name' => 'محمد مهدی',
            'last_name' => 'دهقان منشادی',
            'national_code' => '4420549033',
            'is_admin' => true,
            'verified_at' => now(),
            'is_deletable' => false,
        ]);

        $user->assignRole(RolesEnum::DEVELOPER->value);

        //----------------------------------------------------------------

        $roles = collect(RolesEnum::getAssignableRoles());

        $users = User::factory()->count(10)->unverified()->notAdmin()->create();
        $users->each(function (User $user) {
            AddressUser::factory()->count(mt_rand(0, 4))->onUser($user)->create();
            $user->assignRole([RolesEnum::USER->value]);
        });

        $users = User::factory()->count(10)->unverified()->admin()->create();
        $users->each(function (User $user) use ($roles) {
            AddressUser::factory()->count(mt_rand(0, 4))->onUser($user)->create();
            $user->assignRole(array_map(fn($item) => $item->value, $roles->random(mt_rand(0, $roles->count() - 1))->toArray()));
        });

        $users = User::factory()->count(25)->notAdmin()->create();
        $users->each(function (User $user) {
            AddressUser::factory()->count(mt_rand(0, 4))->onUser($user)->create();
            $user->assignRole([RolesEnum::USER->value]);
        });

        $users = User::factory()->count(25)->admin()->create();
        $users->each(function (User $user) use ($roles) {
            AddressUser::factory()->count(mt_rand(0, 4))->onUser($user)->create();
            $user->assignRole(array_map(fn($item) => $item->value, $roles->random(mt_rand(0, $roles->count() - 1))->toArray()));
        });
    }
}

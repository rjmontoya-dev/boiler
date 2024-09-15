<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{

    public function sampleData(): array
    {
        return [
            [
                'name' => "Roger Montoya",
                'email' => 'rjmontoya-dev@mail.ph',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        ];
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach( $this->sampleData() as $data){
            User::firstOrCreate($data);
        }
    }
}

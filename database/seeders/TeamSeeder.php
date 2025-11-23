<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Attach teams to existing users
        $users = User::all();
        foreach ($users as $user) {
            if ($user->ownedTeams()->count() === 0) {
                $team = Team::create([
                    'name' => $user->name . "'s Team",
                    'owner_id' => $user->id,
                    'description' => 'A default team for initial seeding',
                ]);
                $team->members()->attach($user->id, ['role' => 'owner']);
            }
        }
    }
}

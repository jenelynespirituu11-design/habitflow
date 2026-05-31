<?php

namespace Database\Seeders;

use App\Models\Habit;
use App\Models\HabitLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'full_name'       => 'Test User',
            'email'           => 'test@test.com',
            'password'        => Hash::make('password'),
            'bio'             => 'Building better habits one day at a time.',
            'profile_picture' => null,
        ]);

        $habits = [
            [
                'name'        => 'Morning Meditation',
                'description' => 'Start the day with 10 minutes of mindfulness.',
                'color'       => '#FFB6D9',
                'icon'        => 'sun',
                'frequency'   => 'daily',
                'target_days' => 7,
                'category'    => 'Wellness',
                'status'      => 'active',
                'start_date'  => now()->subDays(14),
            ],
            [
                'name'        => 'Read 20 Pages',
                'description' => 'Read at least 20 pages of a book each day.',
                'color'       => '#FFD6E8',
                'icon'        => 'book',
                'frequency'   => 'daily',
                'target_days' => 7,
                'category'    => 'Learning',
                'status'      => 'active',
                'start_date'  => now()->subDays(10),
            ],
            [
                'name'        => 'Exercise',
                'description' => '30 minutes of physical activity.',
                'color'       => '#FFC8DD',
                'icon'        => 'activity',
                'frequency'   => 'daily',
                'target_days' => 5,
                'category'    => 'Health',
                'status'      => 'active',
                'start_date'  => now()->subDays(7),
            ],
            [
                'name'        => 'Drink 8 Glasses of Water',
                'description' => 'Stay hydrated throughout the day.',
                'color'       => '#FFCCE7',
                'icon'        => 'droplet',
                'frequency'   => 'daily',
                'target_days' => 7,
                'category'    => 'Health',
                'status'      => 'paused',
                'start_date'  => now()->subDays(21),
            ],
            [
                'name'        => 'Weekly Planning',
                'description' => 'Plan goals and priorities for the week ahead.',
                'color'       => '#FFB6D9',
                'icon'        => 'calendar',
                'frequency'   => 'weekly',
                'target_days' => 1,
                'category'    => 'Productivity',
                'status'      => 'completed',
                'start_date'  => now()->subDays(30),
            ],
        ];

        foreach ($habits as $habitData) {
            $habit = $user->habits()->create($habitData);

            // Seed some logs for the last 7 days on active habits
            if ($habitData['status'] === 'active') {
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i)->toDateString();
                    HabitLog::create([
                        'habit_id'    => $habit->id,
                        'user_id'     => $user->id,
                        'logged_date' => $date,
                        'completed'   => (bool) rand(0, 1),
                        'notes'       => null,
                    ]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SettingSeeder::class,
            CategorySeeder::class,
            SliderSeeder::class,
            CauseSeeder::class,
            DonationSeeder::class,
            EventSeeder::class,
            PostSeeder::class,
            TeamSeeder::class,
            TestimonialSeeder::class,
            FaqSeeder::class,
            PartnerSeeder::class,
            PageSeeder::class,
            InboxSeeder::class,
        ]);
    }
}

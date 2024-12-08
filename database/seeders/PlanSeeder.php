<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
                $plan = Plan::create([
                         'name'=>'Plan Test Translation',
                         'title'=>'Test',
                         'fixed'=>'1',
                         'limet_store'=>'20',
                         'app'=>'1',
                         'image'=>'default.png',
                         'description'=>'Test Translation',
                         'setup_fees'=>'22',
                         'monthly'=>'23',
                         'yearly'=>'4234',
                         'quarterly'=>'32',
                         'semi_annual'=>'212',
                         'discount_monthly'=>'2',
                         'discount_quarterly'=>'3',
                         'discount_semi_annual'=>'5',
                         'discount_yearly'=>'1',
                ]);

         $plan->translations()->createMany([
         ['locale' => 'en', 'key' => 'name', 'value' => 'plan Test Translation'],
         ['locale' => 'ar', 'key' => 'name', 'value' => 'خطة لاختبار الترجمة'],
         ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\Contestant;
use App\Models\Criterion;
use App\Models\Exposure;
use App\Models\Judge;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Operator',
            'email' => 'admin@ccdi.edu.ph',
            'password' => bcrypt('password'),
        ]);

        $contest = Contest::create([
            'name' => 'MR. & MS. CCDI 2024',
            'type' => 'double',
            'description' => 'The annual search for the next ambassadors of CCDI.',
            'tabulator_name' => 'Juan Dela Cruz',
            'status' => 'active',
        ]);

        $defaultCriteria = [
            ['name' => 'Poise & Elegance', 'percentage' => 30],
            ['name' => 'Gown & Styling', 'percentage' => 30],
            ['name' => 'Stage Presence', 'percentage' => 20],
            ['name' => 'Overall Impact', 'percentage' => 20],
        ];

        $exposures = [
            ['name' => 'Casual Wear', 'type' => 'preliminary', 'order' => 1, 'weight' => 20, 'status' => 'completed'],
            ['name' => 'Talent Competition', 'type' => 'preliminary', 'order' => 2, 'weight' => 30, 'status' => 'completed'],
            ['name' => 'Evening Gown', 'type' => 'final', 'order' => 3, 'weight' => 50, 'top_n' => 10, 'carry_over' => true, 'status' => 'active'],
            ['name' => 'Q & A', 'type' => 'final', 'order' => 4, 'weight' => 50, 'top_n' => 5, 'carry_over' => true, 'status' => 'locked'],
        ];

        $createdExposures = [];
        foreach ($exposures as $expData) {
            $exposure = $contest->exposures()->create($expData);
            foreach ($defaultCriteria as $crit) {
                $exposure->criteria()->create($crit);
            }
            $createdExposures[] = $exposure;
        }

        $maleContestants = [
            ['number' => 'M01', 'name' => 'Juan Dela Cruz', 'team' => 'Sorsogon City', 'gender' => 'male', 'is_active' => true],
            ['number' => 'M02', 'name' => 'Miguel Santos', 'team' => 'Casiguran', 'gender' => 'male'],
            ['number' => 'M03', 'name' => 'Daniel Reyes', 'team' => 'Donsol', 'gender' => 'male'],
            ['number' => 'M04', 'name' => 'Adrian Villanueva', 'team' => 'Bulan', 'gender' => 'male'],
            ['number' => 'M05', 'name' => 'Patrick Garcia', 'team' => 'Gubat', 'gender' => 'male'],
            ['number' => 'M06', 'name' => 'Antonio Luna', 'team' => 'Irosin', 'gender' => 'male'],
        ];

        foreach ($maleContestants as $con) {
            $contest->contestants()->create($con);
        }

        $femaleContestants = [
            ['number' => 'F01', 'name' => 'Maria Santos', 'team' => 'Sorsogon City', 'gender' => 'female'],
            ['number' => 'F02', 'name' => 'Isabella Cruz', 'team' => 'Casiguran', 'gender' => 'female'],
            ['number' => 'F03', 'name' => 'Sofia Reyes', 'team' => 'Donsol', 'gender' => 'female'],
            ['number' => 'F04', 'name' => 'Camille Villanueva', 'team' => 'Bulan', 'gender' => 'female'],
            ['number' => 'F05', 'name' => 'Patricia Garcia', 'team' => 'Gubat', 'gender' => 'female'],
            ['number' => 'F06', 'name' => 'Angelica Luna', 'team' => 'Irosin', 'gender' => 'female'],
        ];

        foreach ($femaleContestants as $con) {
            $contest->contestants()->create($con);
        }

        $judges = [];
        foreach (['Judge One', 'Judge Two', 'Judge Three', 'Judge Four', 'Judge Five'] as $name) {
            $judges[] = $contest->judges()->create([
                'name' => $name,
                'access_code' => strtoupper(Str::random(6)),
                'is_online' => rand(0, 1),
            ]);
        }

        $contestants = $contest->contestants;
        foreach ($createdExposures as $exposure) {
            if (! in_array($exposure->status, ['completed', 'active'])) {
                continue;
            }

            $isFinal = $exposure->status === 'completed';

            foreach ($judges as $judge) {
                foreach ($contestants as $contestant) {
                    foreach ($exposure->criteria as $criterion) {
                        Score::create([
                            'judge_id' => $judge->id,
                            'contestant_id' => $contestant->id,
                            'criterion_id' => $criterion->id,
                            'exposure_id' => $exposure->id,
                            'score' => rand(75, 98) + (rand(0, 99) / 100),
                            'is_final' => $isFinal,
                        ]);
                    }
                }
            }
        }
    }
}

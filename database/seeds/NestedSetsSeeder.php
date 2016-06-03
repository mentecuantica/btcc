<?php

use Illuminate\Database\Seeder;
use Btcc\Models\User;

class UsersSecondSeeder extends Seeder {
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // Helper function to populate model attributes
        $node = function () {
            $args = implode(' ', func_get_args());

            return ['name' => "User $args"];
        };

        // Create first level nodes
        foreach (range('A', 'F') as $letter) {
            $node0 = User::create($node($letter));

            // Create second level nodes
            foreach (range(1, 3) as $number) {
                $node1 = $node0->children()->create($node($letter, $number));

                // Create third level nodes
                foreach ([
                             'Δ',
                             'Σ',
                             'Ω'
                         ] as $greek) {
                    $node2 = $node1->children()->create($node($letter, $number, $greek));
                }
            }
        }

    }
}

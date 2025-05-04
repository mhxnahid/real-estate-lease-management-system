<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeedPivot extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [

            1 => [
                'permission' => [
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    8,
                    9,
                    10,
                    11,
                    13,
                    14,
                    15,
                    16,
                    18,
                    19,
                    20,
                    23,
                    24,
                    25,
                    28,
                    29,
                    30,
                    33,
                    34,
                    35,
                    50,
                  ],
            ],
            2 => [
                'permission' => [
                    26,
                    27,
                    28,
                    29,
                    30,
                    31,
                    32,
                    33,
                    34,
                    35,
                    36,
                    37,
                    39,
                    47,
                    48,
                    50,
                    21,
                    22,
                    25,
                    23,
                    24,
                    55,
                  ],
            ],
            3 => [
                'permission' => [
                    29,
                    34,
                    54,
                    42,
                    45,
                    36,
                    39,
                    21,
                    24,
                    55,
                  ],
            ],

        ];

        foreach ($items as $id => $item) {
            $role = \App\Models\Role::find($id);

            foreach ($item as $key => $ids) {
                $role->{$key}()->sync($ids);
            }
        }
    }
}

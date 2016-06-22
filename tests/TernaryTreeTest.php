<?php

use Btcc\Utilities\Tree\Ternary\Tree;
use Faker\Factory as Faker;
/**
 * Class TernaryTreeTest
 */
class TernaryTreeTest extends TestCase {

    public static $data = [
        ['id'=>2,'name'=>'P','level'=>1],
        ['id'=>3,'name'=>'R','level'=>1],
        ['id'=>4,'name'=>'S','level'=>1],
        ['id'=>5,'name'=>'SS','level'=>1],
        ['id'=>6,'name'=>'AZ','level'=>1],
        ['id'=>7,'name'=>'ZZ','level'=>1],
        ['id'=>8,'name'=>'AZZZ','level'=>1],
        ['id'=>9,'name'=>'ZZZA','level'=>1],
        ['id'=>10,'name'=>'OAOZ','level'=>1],
        ['id'=>11,'name'=>'XXZ','level'=>1],
        ['id'=>12,'name'=>'ZASA','level'=>1],
        ['id'=>13,'name'=>'OAASZ','level'=>1],
        ['id'=>14,'name'=>'GIA','level'=>1],
        ['id'=>15,'name'=>'HOP','level'=>1],
        ['id'=>16,'name'=>'OPAL','level'=>1],
        ['id'=>17,'name'=>'TOPA','level'=>1],
        ['id'=>18,'name'=>'MIT','level'=>1],
        ['id'=>19,'name'=>'DEAD','level'=>1],
        ['id'=>20,'name'=>'BEAF','level'=>1],
        ['id'=>21,'name'=>'AA','level'=>1],
        ['id'=>22,'name'=>'TWA','level'=>1],
        ['id'=>24,'name'=>'TOPA','level'=>1],
        ['id'=>25,'name'=>'AAS','level'=>1],
        ['id'=>26,'name'=>'PEP','level'=>1],
        ['id'=>26,'name'=>'TA','level'=>1],
        ['id'=>27,'name'=>'BABA','level'=>1],
        ['id'=>28,'name'=>'TsA','level'=>1],
        ['id'=>29,'name'=>'A','level'=>1],
        ['id'=>30,'name'=>'B','level'=>1],
        ['id'=>31,'name'=>'B','level'=>1],
        ['id'=>32,'name'=>'B','level'=>1],
        ['id'=>33,'name'=>'B','level'=>1],
        ['id'=>34,'name'=>'B','level'=>1],
        ['id'=>35,'name'=>'B','level'=>1],
        ['id'=>36,'name'=>'B','level'=>1],
        ['id'=>37,'name'=>'B','level'=>1],
        ['id'=>38,'name'=>'B','level'=>1],
        ['id'=>39,'name'=>'B','level'=>1],
        ['id'=>40,'name'=>'B','level'=>1],

    ];

    public function testCreateTernary() {
        $elements = [
            2,    3,    4,    5,    6,  7,    8,    9,    10,
            11,    12,    13,    14,    15,    16,    17,
            18,    19,   20, 21,22,23,24,25,26,27,28,29,30
        ];

        $tree = new Tree(collect(static::$data));

        $this->assertEquals(39,$tree->getTotalCount());
        //$this->assertEquals(13,$tree->getChunksCount());


    }

    public function testGetMaximumLevel()
    {

        $data = [
        ['id'=>2,'name'=>'P','level'=>1],
        ['id'=>3,'name'=>'R','level'=>1],
        ['id'=>4,'name'=>'S','level'=>1],
        ['id'=>5,'name'=>'SS','level'=>1],
        ['id'=>6,'name'=>'AZ','level'=>1],
        ['id'=>7,'name'=>'ZZ','level'=>1],
         ];
        $tree = new Tree(collect($data));

        $this->assertEquals(2,$tree->getMaximumLevels());


        $elements2= [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18];
        $tree = new Tree($elements2, 1);

        $this->assertEquals(3,$tree->getMaximumLevels());

        $elements3 = range(1,27);
        $tree = new Tree($elements3, 1);
        $this->assertEquals(3,$tree->getMaximumLevels());


        $tree = new Tree(range(1,55), 1);
        $this->assertEquals(4,$tree->getMaximumLevels());


        $tree = new Tree(range(1,81), 1);
        $this->assertEquals(4,$tree->getMaximumLevels());

        $tree = new Tree(range(1,86), 1);
        $this->assertEquals(4,$tree->getMaximumLevels());


    }

    public function testTreeOutputUlLi()
    {
        $tree = new Tree(self::sampleData());

        //$this->setOutputCallback()
        $this->expectOutputRegex('/\<li class=\"ternary-level-3\"\>19, DEAD\<\/li\>/');
        $tree->outputUlLi();
    }

    public function generateFakeData($qty = 10)
    {
        $faker = Faker::create();
        $rawPlainNodes = [];
        foreach (range(1, $qty) as $index) {
            $rawPlainNodes[] = [
                'id'=>$index,
                'name'=>$faker->name,
                'level'=>$faker->numberBetween(1,4),
                'created_at'=>$faker->dateTime,
                'parent_id'=>$faker->numberBetween(1,10)

            ];
        }
    }




    public static function sampleData()
    {
        return [
            [
                'id'    => 2,
                'name'  => 'P',
                'level' => 1
            ],
            [
                'id'    => 3,
                'name'  => 'R',
                'level' => 1
            ],
            [
                'id'    => 4,
                'name'  => 'S',
                'level' => 1
            ],
            [
                'id'    => 5,
                'name'  => 'SS',
                'level' => 1
            ],
            [
                'id'    => 6,
                'name'  => 'AZ',
                'level' => 1
            ],
            [
                'id'    => 7,
                'name'  => 'ZZ',
                'level' => 1
            ],
            [
                'id'    => 8,
                'name'  => 'AZZZ',
                'level' => 1
            ],
            [
                'id'    => 9,
                'name'  => 'ZZZA',
                'level' => 1
            ],
            [
                'id'    => 10,
                'name'  => 'OAOZ',
                'level' => 1
            ],
            [
                'id'    => 11,
                'name'  => 'XXZ',
                'level' => 1
            ],
            [
                'id'    => 12,
                'name'  => 'ZASA',
                'level' => 1
            ],
            [
                'id'    => 13,
                'name'  => 'OAASZ',
                'level' => 1
            ],
            [
                'id'    => 14,
                'name'  => 'GIA',
                'level' => 1
            ],
            [
                'id'    => 15,
                'name'  => 'HOP',
                'level' => 1
            ],
            [
                'id'    => 16,
                'name'  => 'OPAL',
                'level' => 1
            ],
            [
                'id'    => 17,
                'name'  => 'TOPA',
                'level' => 1
            ],
            [
                'id'    => 18,
                'name'  => 'MIT',
                'level' => 1
            ],
            [
                'id'    => 19,
                'name'  => 'DEAD',
                'level' => 1
            ],
            [
                'id'    => 20,
                'name'  => 'BEAF',
                'level' => 1
            ],
            [
                'id'    => 21,
                'name'  => 'AA',
                'level' => 1
            ],
            [
                'id'    => 22,
                'name'  => 'TWA',
                'level' => 1
            ],
            [
                'id'    => 23,
                'name'  => 'BAA',
                'level' => 1
            ],
            [
                'id'    => 24,
                'name'  => 'TOPA',
                'level' => 1
            ],
            [
                'id'    => 25,
                'name'  => 'AAS',
                'level' => 1
            ],
            [
                'id'    => 26,
                'name'  => 'PEP',
                'level' => 1
            ],
            [
                'id'    => 27,
                'name'  => 'BABA',
                'level' => 1
            ],
            [
                'id'    => 28,
                'name'  => 'TsA',
                'level' => 1
            ],
            [
                'id'    => 29,
                'name'  => 'A',
                'level' => 1
            ],
            [
                'id'    => 30,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 31,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 32,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 33,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 34,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 35,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 36,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 37,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 38,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 39,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 40,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 41,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 42,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 43,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 44,
                'name'  => 'B',
                'level' => 1
            ],
            [
                'id'    => 47,
                'name'  => 'AAB',
                'level' => 1
            ],
            [
                'id'    => 66,
                'name'  => 'AAB',
                'level' => 1
            ],

        ];
    }

    public static function sampleData2()
    {
        return [
            [
                'id'    => 2,
                'name'  => 'P',
                'level' => 2,
                'date'=> 2
            ],
            [
                'id'    => 3,
                'name'  => 'R',
                'level' => 1,
                'date'=> 4,
            ],
            [
                'id'    => 4,
                'name'  => 'S',
                'level' => 1,
                'date'=> 1,
            ],
            [
                'id'    => 5,
                'name'  => 'SS',
                'level' => 2,
                'date'=> 2
            ],
            [
                'id'    => 6,
                'name'  => 'AZ',
                'level' => 2,
                'date'=> 21
            ],
            [
                'id'    => 7,
                'name'  => 'ZZ',
                'level' => 1,
                'date'=> 2
            ],
            [
                'id'    => 8,
                'name'  => 'AZZZ',
                'level' => 1,
                'date'=> 66
            ]


        ];
    }
}
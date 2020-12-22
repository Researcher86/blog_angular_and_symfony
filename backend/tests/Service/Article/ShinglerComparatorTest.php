<?php

namespace App\Tests\Service\Article;

use App\Service\Article\ShinglerComparator;
use PHPUnit\Framework\TestCase;

class ShinglerComparatorTest extends TestCase
{
    private ShinglerComparator $comparator;

    protected function setUp(): void
    {
        $this->comparator = new ShinglerComparator();
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $textA
     * @param string $textB
     * @param float $expected
     * @param int $shingleLength
     */
    public function testCompare(string $textA, string $textB, float $expected, int $shingleLength)
    {
        self::assertEquals($expected, $this->comparator->compare($textA, $textB, $shingleLength));
    }

    public function dataProvider()
    {
        return [
            [
                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through. Each step or stage in the process is called a place. You do also define 
                 transitions that describe the action to get from one place to another.',

                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through. Each step or stage in the process is called a place. You do also define 
                 transitions that describe the action to get from one place to another.',

                100,
                5,
            ],
            [
                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through. Each step or stage in the process is called a place. You do also define 
                 transitions that describe the action to get from one place to another.',

                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through. Each step or stage in the process is called a place. You do also define 
                 transitions that describe the action to get from one place to another.',

                100,
                10,
            ],
            [
                '<b>The</b> <b>workflow</b> <b>component</b> <b>gives</b> <b>you</b> <b>an</b> <b>object</b> 
                 <b>oriented</b> <b>way</b> <b>to</b> <b>define</b> <b>a</b> <b>process</b> <b>or</b> <b>a</b> 
                 <b>life</b> <b>cycle</b> <b>that</b> <b>your</b> <b>object</b> <b>goes</b> <b>through</b>.',

                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through.',

                100,
                5,
            ],
            [
                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through. Each step or stage in the process is called a place.',

                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through.',

                76.6,
                5,
            ],
            [
                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through. Each step or stage in the process is called a place.',

                'The workflow component gives you an object oriented way to define a process or a life cycle that 
                 your object goes through.',

                70.27,
                10,
            ],
            [
                'Text 1',
                'Text 2',
                100,
                1,
            ],
        ];
    }
}

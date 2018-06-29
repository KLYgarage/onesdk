<?php

namespace One\Test\Unit;

use One\Collection;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    protected $dummy;

    public function setUp()
    {
        $this->dummy = new Collection(array(
            'name' => 'Prof. Hermann Seip B.Sc.',
            'address' => '688 Anthony Ferry Suite 661
            West Heathermouth, WA 21818',
            'dob' => '1983-02-14',
            'email' => 'jarajoaquin@yahoo.com',
            'atr' => null,
            'age' => 87,
            'thing' => 'Cupiditate libero nulla aperiam culpa. Dolorum consequuntur quae ad. Atque pariatur odio veniam magnam possimus.',
            'posts' => array(
                'title' => 'Veniam libero eveniet molestias cupiditate corporis.'
            )
        ));
    }

    /**
     * @covers Collection::count()
     */
    public function testCountable()
    {
        $this->assertCount(count($this->dummy), $this->dummy);
        return;
    }

    /**
     * @covers Collection::getIterator()
     * @covers Collection::offsetGet()
     */
    public function testIteratorAggregator()
    {
        foreach ($this->dummy as $key => $value) {
            $this->assertEquals($value, $this->dummy[$key]);
        }
        return;
    }

    /**
     * @covers Collection::offsetGet()
     * @covers Collection::offsetSet()
     * @covers Collection::get()
     * @covers Collection::set()
     * @covers Collection::add()
     */
    public function testArrayImplementation()
    {
        $this->assertTrue(is_array($this->dummy->toArray()));
        $this->assertEquals($this->dummy->get('name'), $this->dummy['name']);

        $oldDummy = clone $this->dummy;
        $this->dummy->set('name', 'Olga Angulo Reguera');

        $this->assertThat(
            $oldDummy,
            $this->logicalNot(
                $this->equalTo($this->dummy)
            )
        );

        $this->assertEquals(
            array_keys($oldDummy->toArray()),
            array_keys($this->dummy->toArray())
        );

        $this->dummy->add('statement', 'Aliquid quibusdam ut laboriosam asperiores.');
        $this->assertThat(
            count($oldDummy),
            $this->logicalNot(
                $this->equalTo(
                    count($this->dummy)
                )
            )
        );

        $this->assertTrue(!is_array($this->dummy['statement']));
        $this->dummy->add('statement', 'Alias similique corrupti reprehenderit ex corrupti id.');
        $this->assertTrue(is_array($this->dummy->get('statement')));

        $this->assertTrue(is_array($this->dummy['posts']));
        $this->assertCount(1, $this->dummy['posts']);
        $this->dummy->add('posts', array('title' => 'Nulla eaque modi.'));
        $this->assertTrue(is_array($this->dummy['posts']));
        $this->assertCount(2, $this->dummy['posts']);
    }

    /**
     * @covers Collection::map()
     * @covers Collection::filter()
     */
    public function testMap()
    {
        $newDummy = $this->dummy->map(
            function ($value) {
                return $value;
            }
        );

        $this->assertEquals(
            $this->dummy,
            $newDummy
        );

        $context = ' - added';
        $newDummy = $this->dummy->map(
            function ($value, $key, $context) {
                if (is_string($value)) {
                    return $value . $context;
                }
                return $value;
            },
            $context
        );

        $this->assertNotEquals($this->dummy, $newDummy);
        $this->assertEquals($this->dummy['name'] . $context, $newDummy['name']);

        $newDummy = $this->dummy->filter(
            function ($value) {
                return !empty($value) ? true : false;
            }
        );

        $this->assertNotEquals($this->dummy, $newDummy);
        $this->assertNotEquals(count($this->dummy), count($newDummy));
        $this->assertTrue(!isset($newDummy['atr']));
    }
}

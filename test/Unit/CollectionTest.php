<?php declare(strict_types=1);

namespace One\Test\Unit;

use One\Collection;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Collection
     * @var \One\Collection
     */
    protected $dummy;

    protected function setUp(): void
    {
        $this->dummy = new Collection([
            'name' => 'Prof. Hermann Seip B.Sc.',
            'address' => '688 Anthony Ferry Suite 661
            West Heathermouth, WA 21818',
            'dob' => '1983-02-14',
            'email' => 'jarajoaquin@yahoo.com',
            'atr' => null,
            'age' => 87,
            'thing' => 'Cupiditate libero nulla aperiam culpa. Dolorum consequuntur quae ad. Atque pariatur odio veniam magnam possimus.',
            'posts' => [
                'title' => 'Veniam libero eveniet molestias cupiditate corporis.',
            ],
        ]);
    }

    /**
     * @covers Collection::count()
     */
    public function testCountable(): void
    {
        $this->assertCount(count($this->dummy), $this->dummy);
        return;
    }

    /**
     * @covers Collection::getIterator()
     * @covers Collection::offsetGet()
     */
    public function testIteratorAggregator(): void
    {
        foreach ($this->dummy as $key => $value) {
            $this->assertSame($value, $this->dummy[$key]);
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
    public function testArrayImplementation(): void
    {
        $this->assertTrue(is_array($this->dummy->toArray()));
        $this->assertSame($this->dummy->get('name'), $this->dummy['name']);

        $oldDummy = clone $this->dummy;
        $this->dummy->set('name', 'Olga Angulo Reguera');

        $this->assertThat(
            $oldDummy,
            $this->logicalNot(
                $this->equalTo($this->dummy)
            )
        );

        $this->assertSame(
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

        $this->assertTrue(! is_array($this->dummy['statement']));
        $this->dummy->add('statement', 'Alias similique corrupti reprehenderit ex corrupti id.');
        $this->assertTrue(is_array($this->dummy->get('statement')));

        $this->assertTrue(is_array($this->dummy['posts']));
        $this->assertCount(1, $this->dummy['posts']);
        $this->dummy->add('posts', ['title' => 'Nulla eaque modi.']);
        $this->assertTrue(is_array($this->dummy['posts']));
        $this->assertCount(2, $this->dummy['posts']);
    }

    /**
     * @covers Collection::map()
     * @covers Collection::filter()
     */
    public function testMap(): void
    {
        $newDummy = $this->dummy->map(
            function ($value) {
                return $value;
            }
        );


        $this->assertInstanceOf(Collection::class, $this->dummy);

        $this->assertInstanceOf(Collection::class, $newDummy);

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

        $this->assertNotSame($this->dummy, $newDummy);
        $this->assertSame($this->dummy['name'] . $context, $newDummy['name']);

        $newDummy = $this->dummy->filter(
            function ($value) {
                return ! empty($value) ? true : false;
            }
        );

        $this->assertNotSame($this->dummy, $newDummy);
        $this->assertNotSame(count($this->dummy), count($newDummy));
        $this->assertTrue(! isset($newDummy['atr']));
    }
}

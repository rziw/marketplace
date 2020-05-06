<?php

namespace Tests\Unit;

use App\Helpers\OrderHandler;
use PHPUnit\Framework\TestCase;

class OrderHandlerTest extends TestCase
{
    private $product_price;
    private $ordered_count;

    public function setUp(): void
    {
        parent::setUp();

        $this->ordered_count = 5;
        $this->product_price = 10000;
    }

    /**
     * @test
     */
    public function calculateOrderPriceProperly()
    {
        $orderHandler = new OrderHandler();
        $calculated_price = $this->invokeMethod($orderHandler,
            'calculatePriceAction',
            array($this->product_price, $this->ordered_count));

        $this->assertEquals(50000, $calculated_price);
    }


    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}

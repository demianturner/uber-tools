<?php
require_once ('Uber.php');
/**
 * Uber_Component test case.
 */
class Uber_Lazy_Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Uber_Component
     */
    private $Uber_Component;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        Uber::init();
        Uber_Loader::registerNamespace('Example', dirname(dirname(__FILE__)) . DS . 'fixtures' . DS . 'examples');
        parent::setUp();
        // TODO Auto-generated Uber_Component_Test::setUp()
        $this->Helper = new Example_Helper();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated Uber_Component_Test::tearDown()
        $this->Helper = null;
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {// TODO Auto-generated constructor
}

    public function test_helper_one()
    {
        $this->assertEquals('Example_Helper_One', get_class($this->Helper->one));
        $this->assertEquals(1, $this->Helper->one->one());
    }

    public function test_helper_two()
    {
        $this->assertEquals('Example_Helper_Two', get_class($this->Helper->two));
        $this->assertEquals(2, $this->Helper->two->two());
    }

    public function test_helper_three()
    {
        $this->assertEquals('Example_Helper_Three', get_class($this->Helper->three));
        $this->assertEquals(3, $this->Helper->three->three());
        $this->assertEquals('say hi from parent', $this->Helper->three->say_hi_from_parent());
    }
}


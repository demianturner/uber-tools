<?php
require_once ('Uber.php');
/**
 * Uber_Object test case.
 */
! defined('UBER_TEST_DIR') ? define('UBER_TEST_DIR', dirname(dirname(__FILE__))) : null;
/**
 *  test case.
 */
class Uber_Object_Test extends PHPUnit_Framework_TestCase
{

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        Uber::init();
        Uber_Loader::addAutoloadPattern(array(
        	'str_replace' => array('_' => '/') , 
        	'suffix' => '.php' , 
        	'basedir' => dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'object'));
        parent::setUp();
        // TODO Auto-generated Uber_Object_Test::setUp()
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated Uber_Object_Test::tearDown()
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {// TODO Auto-generated constructor
}

    public function test_wrapped_object_readonly_attributes()
    {
        $dummy = new Dummy();
        $wrappedDummy = new Uber_Object($dummy);
        $this->assertEquals($dummy->var1, $wrappedDummy->var1);
        $dummy->var1 = time();
        $this->assertEquals($dummy->var1, $wrappedDummy->var1);
        try {
            $wrappedDummy->var1 = time();
            $this->assertTrue(false, 'Object is readonly, should not allow setting the value');
        } catch (Uber_Object_Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function test_wrapped_object_readonly_limited_attributes()
    {
        $dummy = new Dummy();
        $wrappedDummy = new Uber_Object($dummy, array('var1'));
        $this->assertEquals($dummy->var1, $wrappedDummy->var1);
        $dummy->var1 = time();
        $this->assertEquals($dummy->var1, $wrappedDummy->var1);
        try {
            $wrappedDummy->var2;
            $this->assertTrue(false, 'Object is readonly limited to var1, should not allow reading the value of var2');
        } catch (Uber_Object_Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function test_wrapped_object_writable_limited_attributes()
    {
        $dummy = new Dummy();
        $wrappedDummy = new Uber_Object($dummy, array('var1' , 'var2'), array('var2'));
        $this->assertEquals($dummy->var1, $wrappedDummy->var1);
        $wrappedDummy->var2 = time();
        $this->assertEquals($dummy->var2, $wrappedDummy->var2);
        try {
            $wrappedDummy->var1 = time();
            $this->assertTrue(false, 'Object is writeable limited to var2, should not allow writing the value of var1');
        } catch (Uber_Object_Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function test_wrapped_object_callable_methods()
    {
        $dummy = new Dummy();
        $wrappedDummy = new Uber_Object($dummy, array('var1' , 'var2'), array('var2'), array('call1'));
        $this->assertEquals($dummy->call1(), $wrappedDummy->call1());
        try {
            /**
             * Non accessible method
             */
            $this->assertNotEquals($dummy->call2(), $wrappedDummy->call2());
        } catch (Uber_Object_Exception $e) {
            $this->assertTrue(true);
        }
    }
}


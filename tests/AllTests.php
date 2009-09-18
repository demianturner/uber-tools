<?php
require_once 'tests/unit/Uber_Lazy_Test.php';
require_once 'tests/unit/Uber_Loader_Test.php';
require_once 'tests/unit/Uber_Object_Test.php';
require_once 'tests/unit/Uber_Plugin_Test.php';
/**
 * Static test suite.
 */
class AllTests extends PHPUnit_Framework_TestSuite
{

    /**
     * Constructs the test suite handler.
     */
    public function __construct()
    {
        $this->setName('AllTests');
        $this->addTestSuite('Uber_Lazy_Test');
        $this->addTestSuite('Uber_Loader_Test');
        $this->addTestSuite('Uber_Object_Test');
        $this->addTestSuite('Uber_Plugin_Test');
    }

    /**
     * Creates the suite.
     */
    public static function suite()
    {
        return new self();
    }
}


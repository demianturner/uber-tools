<?php
require_once ('Uber.php');
/**
 * Uber_Loader test case.
 */
! defined('UBER_TEST_DIR') ? define('UBER_TEST_DIR', dirname(dirname(__FILE__))) : null;
class Uber_Loader_Test extends PHPUnit_Framework_TestCase
{

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        Uber::init();
        Uber_Loader::resetAutoloadPatterns();
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {}

    public function test_is_valid_regex_unknown_modifier()
    {
        $regex = '/.*/Y';
        try {
            Uber_Loader::validateRegex($regex);
            $this->assertFalse(true, 'Should throw an exception');
        } catch (Uber_Loader_Exception $e) {
            $this->assertRegExp('/Invalid regular expression.*?Unknown modifier.*?/', $e->getMessage());
        }
    }

    public function test_is_valid_regex_nothing_to_repeat()
    {
        $regex = '/*/';
        try {
            Uber_Loader::validateRegex($regex);
            $this->assertFalse(true, 'Should throw an exception');
        } catch (Uber_Loader_Exception $e) {
            $this->assertRegExp('/Invalid regular expression.*?Compilation failed: nothing to repeat at offset 0/', $e->getMessage());
        }
    }

    public function test_is_valid_regex_nothing_to_repeat_and_leaving_out_delimiters()
    {
        $regex = '*';
        try {
            Uber_Loader::validateRegex($regex);
            $this->assertFalse(true, 'Should throw an exception');
        } catch (Uber_Loader_Exception $e) {
            $this->assertRegExp('/Invalid regular expression:.*?Compilation failed: nothing to repeat at offset 0/', $e->getMessage());
        }
    }

    public function test_is_valid_regex_when_leaving_out_delimiter()
    {
        $regex = 'Something.*';
        try {
            Uber_Loader::validateRegex($regex);
            $this->assertTrue(true, 'Should not throw an exception, delimiter // is added inside');
        } catch (Uber_Loader_Exception $e) {
            $this->assertFalse(true, $e->getMessage());
        }
    }

    public function test_invalid_class_name_in_call_user_func_array()
    {
        $className = '-Cannot-Exist';
        try {
            call_user_func_array(array($className , 'someMethod'), array(1));
            $this->assertTrue(false);
        } catch (Uber_Loader_Exception $e) {
            $this->assertEquals(- 3, $e->getCode());
        }
    }

    public function test_autoload_single_class()
    {
        $file = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS . 'SomethingElse.php';
        Uber_Loader::addAutoloadPattern(array('NothingToDoWithIt' => $file));
        try {
            $class = new NothingToDoWithIt();
            $this->assertType('NothingToDoWithIt', $class);
        } catch (Uber_Loader_Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_autoload_single_classes_with_same_class_name()
    {
        $file1 = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS . 'same' . DS . 'version1' . DS . 'ClassTestVersion.php';
        $file2 = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS . 'same' . DS . 'version2' . DS . 'ClassTestVersion.php';
        Uber_Loader::resetAutoloadPatterns();
        Uber_Loader::addAutoloadPattern(array('ClassTestVersion' => $file1), 1);
        Uber_Loader::addAutoloadPattern(array('ClassTestVersion' => $file2), 2);
        try {
            $class = new ClassTestVersion();
            $this->assertType('ClassTestVersion', $class);
            $this->assertEquals(2, $class->version);
        } catch (Uber_Loader_Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_autoload_invalid_pattern()
    {
        $this->setExpectedException('Uber_Loader_Exception');
        Uber_Loader::addAutoloadPattern(array('by_name' => array('Class' => 'SomethingElse.php')));
    }

    public function test_autoload_preg_match()
    {
        $basedir = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS;
        Uber_Loader::addAutoloadPattern(array('preg_match' => 'System_(.*)' , 'filename' => 'System/$1.php' , 'basedir' => $basedir));
        try {
            $class = new System_Module1();
            $this->assertType('System_Module1', $class);
        } catch (Uber_Loader_Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_autoload_preg_match_eval()
    {
        $basedir = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS;
        Uber_Loader::addAutoloadPattern(array('preg_match' => 'Lower_(.*)' , 'filename' => 'strtolower("lower/$1.php")' , 'basedir' => $basedir , 'eval' => true));
        try {
            $class = new Lower_Case();
            $this->assertType('Lower_Case', $class);
        } catch (Uber_Loader_Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_lazy_autoload_pear_style_with_str_replace_as_string()
    {
        $basedir = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS;
        Uber_Loader::addAutoloadPattern(array('str_replace' => '_' , 'replacement' => DS , 'basedir' => $basedir , 'suffix' => '.php'));
        try {
            $c = new Lazy_System_File();
            $this->assertType('Lazy_System_File', $c);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_lazy_autoload_pear_style_with_str_replace_as_array()
    {
        $basedir = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS;
        Uber_Loader::addAutoloadPattern(array('str_replace' => array('_') , 'replacement' => array(DS) , 'basedir' => $basedir , 'suffix' => '.php'));
        try {
            $c = new Lazy_System_Dir();
            $this->assertType('Lazy_System_Dir', $c);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_lazy_autoload_pear_style_with_preg_replace_as_string()
    {
        $basedir = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS;
        Uber_Loader::addAutoloadPattern(array('preg_replace' => '_' , 'replacement' => DS , 'basedir' => $basedir , 'suffix' => '.php'));
        try {
            $c = new Lazy_System_Error2();
            $this->assertType('Lazy_System_Error2', $c);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_lazy_autoload_pear_style_with_preg_replace_as_array()
    {
        $basedir = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS;
        Uber_Loader::addAutoloadPattern(array('preg_replace' => array('_') , 'replacement' => array(DS) , 'basedir' => $basedir , 'suffix' => '.php'));
        try {
            $c = new Lazy_System_Error();
            $this->assertType('Lazy_System_Error', $c);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }
    public function test_lazy_autoload_pear_style_with_nonexistant_file()
    {
        $basedir = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS;
        Uber_Loader::registerNamespace('Lazy');
        try {
            $c = new Lazy_Idont_Exist();
        } catch (Exception $e) {
            $this->assertTrue(true);
        }
    }
    public function test_lazy_autoload_classes()
    {
        $basedir = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS . 'module' . DS;
        Uber_Loader::addAutoloadPattern(array('classes' => array('A' , 'B') , 'filename' => '$class' , 'basedir' => $basedir , 'suffix' => '.php'));
        try {
            $a = new A();
            $this->assertType('A', $a);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
        try {
            $b = new B();
            $this->assertType('B', $b);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }

    public function test_lazy_autoload_set_patterns()
    {
        $basedir1 = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS . 'module' . DS;
        $basedir2 = UBER_TEST_DIR . DS . 'fixtures' . DS . 'autoloader' . DS . 'lib' . DS;
        Uber_Loader::setAutoloadPatterns(array(array('classes' => array('A' , 'B' , 'C') , 'filename' => '$class' , 'basedir' => $basedir1 , 'suffix' => '.php') , array('str_replace' => array('_') , 'replacement' => array(DS) , 'basedir' => $basedir2 , 'suffix' => '.php')));
        try {
            $a = new C();
            $this->assertType('C', $a);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
        try {
            $c = new Lazy_System_OutOfIdeas();
            $this->assertType('Lazy_System_OutOfIdeas', $c);
        } catch (Exception $e) {
            $this->assertTrue(false);
        }
    }
    
    public function test_class_exists_call_without_exception()
    {
        Uber_Loader::registerNamespace('Something',true,false);
        $this->assertFalse(class_exists('Something_Foo',true));
        $this->assertFalse(class_exists('Something_Foo',false));
    }
    
    public function test_class_exists_call_with_exception()
    {
        Uber_Loader::registerNamespace('Something2',true,true);
        $this->assertTrue(class_exists('Something2_Foo',true));
        $this->assertTrue(class_exists('Something2_Foo',false));
    }
}


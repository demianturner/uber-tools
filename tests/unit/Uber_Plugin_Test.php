<?php
require_once 'Uber.php';
/**
 * Test suite.
 *
 * @package Uber
 * @author  Demian Turner <demian@phpkitchen.com>
 */
class Uber_Plugin_Test extends PHPUnit_Framework_TestCase
{

    function setup()
    {
        /**
         * Uber::init() always on top, will require the Autoloader class etc.
         */
        Uber::init();
        $path = dirname(dirname(__FILE__));
        Uber_Loader::registerNamespace('Xmpl', $path . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'plugin');
        $GLOBALS['UBER']['actions'] = array();
        parent::setUp();
    }

    function tearDown()
    {
        $disp = Uber_Event_Dispatcher::getInstance();
        $disp->reset();
        $GLOBALS['UBER'] = array();
        parent::tearDown();
    }

    public function testPluginInstantiation()
    {
        $foo = new Xmpl_Plugin_Foo();
        $this->assertType('Uber_Event_Listener_Interface', $foo);
    }

    public function testEventInstantiation()
    {
        $e = new Xmpl_Event($this, 'Xmpl_Event::HOOK_FIRST');
        $this->assertType('Uber_Event', $e);
    }

    public function testDispatcherInstantiation()
    {
        //	get plugin
        $plugin = new Xmpl_Plugin_Foo();
        $d = Uber_Event_Dispatcher::getInstance();
        $this->assertType('Uber_Event_Dispatcher', $d);
        $d->addEventListener('XMPL_Event::HOOK_FIRST', $plugin);
    }

    public function testPluginActions()
    {
        //	get plugin
        $plugin = new Xmpl_Plugin_Baz();
        $disp = Uber_Event_Dispatcher::getInstance();
        //	register plugin on an event
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $plugin);
        // invoking an event in the app
        $this->assertFalse(isset($GLOBALS['UBER']['actions']['test1']));
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST', array('test_value' => 'quux')));
        //	plugin invoked actions
        $this->assertTrue(isset($GLOBALS['UBER']['actions']['test1']));
        $this->assertSame($GLOBALS['UBER']['actions']['test1'], 'quux');
    }

    public function testPluginActionsMultiple()
    {
        //	get plugin
        $pluginBaz = new Xmpl_Plugin_Baz();
        $pluginQuux = new Xmpl_Plugin_Quux();
        $disp = Uber_Event_Dispatcher::getInstance();
        //	register plugin on an event
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginBaz);
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginQuux);
        // invoking an event in the app
        $this->assertFalse(isset($GLOBALS['UBER']['actions']['test2']));
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST', array('test_value' => 'quux')));
        //	plugin invoked actions
        $this->assertTrue(isset($GLOBALS['UBER']['actions']['test2']));
        $this->assertSame($GLOBALS['UBER']['actions']['test2'], 'quux');
    }

    public function testPluginFilters()
    {
        //	get plugin
        $plugin = new Xmpl_Plugin_Foo();
        $disp = Uber_Event_Dispatcher::getInstance();
        //	register plugin on an event
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $plugin);
        // invoking an event in the app
        $ctx = new Xmpl_Context();
        $this->assertSame('this is some text', $ctx->get());
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST'), $ctx);
        //	plugin transformed data
        $this->assertSame('THIS IS SOME TEXT', $ctx->get());
    }

    public function testPluginMultipleFilters()
    {
        //	get plugin
        $pluginFoo = new Xmpl_Plugin_Foo(); // capitalise
        $pluginBar = new Xmpl_Plugin_Bar(); // reverse
        $disp = Uber_Event_Dispatcher::getInstance();
        //	register plugins on an event
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginFoo);
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginBar);
        // invoking an event in the app
        $ctx = new Xmpl_Context();
        $this->assertSame('this is some text', $ctx->get());
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST'), $ctx);
        //	plugin transformed data
        $this->assertSame('TXET EMOS SI SIHT', $ctx->get());
    }

    public function testPluginCancelling()
    {
        //  get plugin
        $pluginBaz = new Xmpl_Plugin_Baz();
        $pluginQuux = new Xmpl_Plugin_Quux();
        $pluginCancel = new Xmpl_Plugin_Cancel();
        $pluginException = new Xmpl_Plugin_Exception();
        $disp = Uber_Event_Dispatcher::getInstance();
        //  register plugins on an event
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginBaz);
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginQuux);
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginCancel);
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginException); // never reached
        $this->assertFalse(defined('TEST_CONSTANT_EVENT_CANCELLED'));
        // exception never thrown
        try {
            $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST', array('test_value' => 'quux')));
            $this->assertTrue(true);
        } catch (Uber_Plugin_Exception $e) {
            $this->assertTrue(false, 'exception never thrown');
        }
        $this->assertTrue(defined('TEST_CONSTANT_EVENT_CANCELLED'));
        $this->assertType('Uber_Event', $e);
        $this->assertTrue($e->isCancelled());
    }

    public function testPluginRemoveListener()
    {
        $pluginBaz = new Xmpl_Plugin_Baz();
        $pluginQuux = new Xmpl_Plugin_Quux();
        $pluginCancel = new Xmpl_Plugin_Cancel();
        $disp = Uber_Event_Dispatcher::getInstance();
        //  register plugins on an event
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginBaz);
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginQuux);
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginCancel);
        //print_r($disp);
        $this->assertSame($disp->getListenerCount('XMPL_Event::HOOK_FIRST'), 3);
        //  remove a listener
        $disp->removeEventListener('XMPL_Event::HOOK_FIRST', $pluginCancel);
        $this->assertSame($disp->getListenerCount('XMPL_Event::HOOK_FIRST'), 2);
    }

    public function testPluginAddListenerToQueue()
    {
        $pluginBaz = new Xmpl_Plugin_Baz();
        $disp = Uber_Event_Dispatcher::getInstance();
        //  register plugins on an event
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginBaz);
        $this->assertFalse(isset($GLOBALS['UBER']['actions']['test1']));
        $this->assertSame($disp->getListenerCount('XMPL_Event::HOOK_FIRST'), 1);
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST', array('test_value' => 'quux')), null, true);
        $this->assertTrue(isset($GLOBALS['UBER']['actions']['test1']));
        //  add a listener AFTER event
        $pluginQuux = new Xmpl_Plugin_Quux();
        $this->assertFalse(isset($GLOBALS['UBER']['actions']['test2']));
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginQuux);
        $this->assertTrue(isset($GLOBALS['UBER']['actions']['test2']));
    }

    public function testPluginAddGlobalListener()
    {
        $pluginSelfAppend = new Xmpl_Plugin_SelfAppend();
        $disp = Uber_Event_Dispatcher::getInstance();
        //  register plugins on an event
        $disp->addGlobalListener($pluginSelfAppend);
        $GLOBALS['UBER']['actions']['appended_names'] = '';
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST'));
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_SECOND'));
        $this->assertSame($GLOBALS['UBER']['actions']['appended_names'], 'XMPL_Event::HOOK_FIRSTXMPL_Event::HOOK_SECOND');
    }

    public function testPluginRemoveGlobalListener()
    {
        $pluginFoo = new Xmpl_Plugin_Foo();
        $pluginSelfAppend = new Xmpl_Plugin_SelfAppend();
        $disp = Uber_Event_Dispatcher::getInstance();
        //  register plugins on an event
        $disp->addGlobalListener($pluginFoo);
        $disp->addGlobalListener($pluginSelfAppend);
        $GLOBALS['UBER']['actions']['appended_names'] = ''; // init for SelfAppend
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST'));
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_SECOND'));
        $ret = $disp->removeGlobalListener($pluginFoo);
        $this->assertTrue($ret);
    }

    public function testPluginGetAllListeners()
    {
        $pluginFoo = new Xmpl_Plugin_Foo();
        $pluginBar = new Xmpl_Plugin_Bar();
        $disp = Uber_Event_Dispatcher::getInstance();
        //  register plugins on an event
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginFoo);
        $disp->addEventListener('XMPL_Event::HOOK_FIRST', $pluginBar);
        $e = $disp->triggerEvent(new Xmpl_Event($this, 'XMPL_Event::HOOK_FIRST'));
        $coll = $disp->getEventListeners('XMPL_Event::HOOK_FIRST');
        $this->assertType('Uber_Event_Listener_Collection', $coll);
        $this->assertSame(count($coll), 2);
    }

    public function testPluginValidation()
    {
        $this->markTestIncomplete("validation test not implemented");
    }
}
?>
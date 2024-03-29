<?php
/**
 * DbSync
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/php-dbsync/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to maks.slesarenko@gmail.com so we can send you a copy immediately.
 *
 * @category DbSync
 * @package  Tests
 * @license  http://code.google.com/p/php-dbsync/wiki/License   New BSD License
 * @version  $Id$
 */

/**
 * DbSync_FileAdapter_SfYamlTest
 *
 * @group    file
 * @category DbSync
 * @package  Tests
 * @version  $Id$
 */
class DbSync_FileAdapter_SfYamlTest extends PHPUnit_Framework_TestCase
{
    protected $_path;
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->_path = 'exampleDir';
        vfsStream::setup($this->_path);

    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated Console::tearDown()
        parent::tearDown();
    }

    /**
     * Get mock
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getMock($methods = null)
    {
        return $this->getMock('DbSync_FileAdapter_SfYaml', $methods, array($this->_path));
    }

    /**
     * getFilePath
     */
    public function test_getFilePath_schema()
    {
        $adapter = $this->_getMock();

        $tableName = 'users';

        $result = vfsStream::url($this->_path) . '/schema/' . $tableName
                . '.' . DbSync_FileAdapter_SfYaml::FILE_EXTENSION;

        $model = $this->getMock('DbSync_Model_Table_Schema', null, array(), '', false);
        $model->setTableName($tableName);

        $this->assertEquals($result, $adapter->getFilePath($model));
    }

    /**
     * getFilePath
     */
    public function test_getFilePath_data()
    {
        $adapter = $this->_getMock();

        $tableName = 'users';

        $result = vfsStream::url($this->_path) . '/data/' . $tableName
                . '.' . DbSync_FileAdapter_SfYaml::FILE_EXTENSION;

        $model = $this->getMock('DbSync_Model_Table_Data', array(), array(), '', false);
        $model->expects($this->any())
              ->method('getTableName')
              ->will($this->returnValue($tableName));

        $this->assertEquals($result, $adapter->getFilePath($model));
    }

    /**
     * getFilePath
     */
    public function test_getFilePath_triggers()
    {
        $adapter = $this->_getMock();

        $triggerName = 'users';

        $result = vfsStream::url($this->_path) . '/trigger/' . $triggerName
                . '.' . DbSync_FileAdapter_SfYaml::FILE_EXTENSION;

        $model = $this->getMock('DbSync_Model_Table_Trigger', array(), array(), '', false);
        $model->expects($this->any())
              ->method('getTriggerName')
              ->will($this->returnValue($triggerName));

        $this->assertEquals($result, $adapter->getFilePath($model));
    }

    /**
     * getFilePath
     *
     */
    public function test_getFilePath_exception()
    {
        $adapter = $this->_getMock();

        $model = $model = $this->getMock('DbSync_Model_Table_AbstractTable', array(), array(), 'UnknownModel', false);

        try {
            $adapter->getFilePath($model);
            $this->fail(__METHOD__ . ' - shoult fail');
        } catch (Exception $e) {
            $this->assertEquals("Model 'UnknownModel' is not supported", $e->getMessage());
        }
    }

    /**
     * getTableList
     */
    public function test_getTableList()
    {
        mkdir(vfsStream::url($this->_path) . '/schema/', 0777, true);

        fopen(vfsStream::url($this->_path) . '/schema/users.yml', "a");
        fopen(vfsStream::url($this->_path) . '/schema/setting.yml', "a");

        $adapter = $this->_getMock(array('getIterator'));
        $adapter->expects($this->once())
                ->method('getIterator')
                ->with($this->equalTo('schema/*.yml'))
                ->will($this->returnValue(
                    array(
                        new SplFileObject(vfsStream::url($this->_path) . '/schema/users.yml'),
                        new SplFileObject(vfsStream::url($this->_path) . '/schema/setting.yml')
                    )
                ));


        $model = $this->getMock('DbSync_Model_Table_Schema', null, array(), '', false);

        $this->assertEquals(
            array('users', 'setting'),
            $adapter->getTableList($model)
        );
    }

    /**
     * getTableList
     */
    public function test_getTableList_data()
    {
        mkdir(vfsStream::url($this->_path) . '/data/', 0777, true);

        fopen(vfsStream::url($this->_path) . '/data/users.yml', "a");
        fopen(vfsStream::url($this->_path) . '/data/setting.yml', "a");

        $adapter = $this->_getMock(array('getIterator'));
        $adapter->expects($this->once())
                ->method('getIterator')
                ->with($this->equalTo('data/*.yml'))
                ->will($this->returnValue(
                    array(
                        new SplFileObject(vfsStream::url($this->_path) . '/data/users.yml'),
                        new SplFileObject(vfsStream::url($this->_path) . '/data/setting.yml')
                    )
                ));


        $model = $this->getMock('DbSync_Model_Table_Data', null, array(), '', false);

        $this->assertEquals(
            array('users', 'setting'),
            $adapter->getTableList($model)
        );
    }
    /**
     * getTableList
     */
    public function test_getTableList_trigger()
    {
        mkdir(vfsStream::url($this->_path) . '/trigger/', 0777, true);

        fopen(vfsStream::url($this->_path) . '/trigger/users.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/setting.yml', "a");

        $adapter = $this->_getMock(array('getIterator'));
        $adapter->expects($this->once())
                ->method('getIterator')
                ->with($this->equalTo('trigger/*.yml'))
                ->will($this->returnValue(
                    array(
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/users.yml'),
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/setting.yml')
                    )
                ));


        $model = $this->getMock('DbSync_Model_Table_Trigger', null, array(), '', false);

        $this->assertEquals(
            array('users', 'setting'),
            $adapter->getTableList($model)
        );
    }

    /**
     * getTableList
     *
     */
    public function test_getTableList_exception()
    {
        $adapter = $this->_getMock();

        $model = $model = $this->getMock('DbSync_Model_Table_AbstractTable', array(), array(), 'UnknownModel2', false);

        try {
            $adapter->getTableList($model);
            $this->fail(__METHOD__ . ' - shoult fail');
        } catch (Exception $e) {
            $this->assertEquals("Model 'UnknownModel2' is not supported", $e->getMessage());
        }
    }


    /**
     * getTriggerList
     */
    public function test_getTriggerList()
    {
        mkdir(vfsStream::url($this->_path) . '/trigger/', 0777, true);

        fopen(vfsStream::url($this->_path) . '/trigger/trigger1.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/trigger2.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/trigger3.yml', "a");

        $adapter = $this->_getMock(array('getIterator'));
        $adapter->expects($this->once())
                ->method('getIterator')
                ->with($this->equalTo('trigger/*.yml'))
                ->will($this->returnValue(
                    array(
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/trigger1.yml'),
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/trigger2.yml'),
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/trigger3.yml')
                    )
                ));

        $this->assertEquals(
            array('trigger1', 'trigger2', 'trigger3'),
            $adapter->getTriggerList(array())
        );
    }

    /**
     * getTriggerList
     */
    public function test_getTriggerList_forTable()
    {
        $tableName = 'users';

        mkdir(vfsStream::url($this->_path) . '/trigger/', 0777, true);

        fopen(vfsStream::url($this->_path) . '/trigger/trigger1.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/trigger2.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/trigger3.yml', "a");

        $adapter = $this->_getMock(array('getIterator', 'getTableByTrigger'));
        $adapter->expects($this->once())
                ->method('getIterator')
                ->with($this->equalTo("trigger/*.yml"))
                ->will($this->returnValue(
                    array(
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/trigger1.yml'),
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/trigger2.yml'),
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/trigger3.yml')
                    )
                ));

        $adapter->expects($this->exactly(3))
                ->method('getTableByTrigger')
                ->will($this->returnCallback(array($this, 'getTriggerListCallback')));

        $this->assertEquals(
            array('trigger1'),
            $adapter->getTriggerList(array($tableName))
        );
    }

    public function getTriggerListCallback($arg)
    {
        if ('trigger1' == $arg) {
            return 'users';
        }
    }

    /**
     * getTableByTrigger
     */
    public function test_getTableByTrigger()
    {
        mkdir(vfsStream::url($this->_path) . '/trigger/', 0777, true);

        fopen(vfsStream::url($this->_path) . '/trigger/trigger1.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/trigger2.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/trigger3.yml', "a");

        $adapter = $this->_getMock(array('getIterator', 'load'));
        $adapter->expects($this->once())
                ->method('getIterator')
                ->with($this->equalTo("trigger/trigger1.yml"))
                ->will($this->returnValue(
                    array(
                        new SplFileObject(vfsStream::url($this->_path) . '/trigger/trigger1.yml'),
                    )
                ));
        $adapter->expects($this->once())
                ->method('load')
                ->with($this->equalTo(vfsStream::url($this->_path) . '/trigger/trigger1.yml'))
                ->will($this->returnValue(array('table' => 'users')));

        $this->assertEquals(
            'users',
            $adapter->getTableByTrigger('trigger1')
        );
    }

    /**
     * getTableByTrigger
     */
    public function test_getTableByTrigger_notFound()
    {
        mkdir(vfsStream::url($this->_path) . '/trigger/', 0777, true);

        fopen(vfsStream::url($this->_path) . '/trigger/trigger1.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/trigger2.yml', "a");
        fopen(vfsStream::url($this->_path) . '/trigger/trigger3.yml', "a");

        $adapter = $this->_getMock(array('getIterator'));
        $adapter->expects($this->once())
                ->method('getIterator')
                ->will($this->returnValue(array()));


        $this->assertEquals('', $adapter->getTableByTrigger('trigger4'));
    }

    /**
     * getIterator
     */
    public function test_getIterator()
    {
        $adapter = $this->_getMock();
        $iterator = $adapter->getIterator('trigger/*');

        $this->assertInstanceOf('GlobIterator', $iterator);
    }

    /**
     * write
     */
    public function test_write()
    {
        $data = array(
            'name' => 'somename',
            'value' => 'somevalue'
        );

        $file = vfsStream::url($this->_path) . '/config.yml';
        fopen($file, 'a');

        $adapter = $this->_getMock();
        $adapter->write($file, $data);


        $this->assertEquals(
            "name: somename\nvalue: somevalue\n",
            file_get_contents($file)
        );
    }
}


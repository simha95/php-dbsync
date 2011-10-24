<?php
/**
 * DbSync
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://code.google.com/p/phplizard/wiki/License
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to maks.slesarenko@gmail.com so we can send you a copy immediately.
 *
 * @category DbSync
 * @package  Tests
 * @license  http://code.google.com/p/php-dbsync/wiki/License   New BSD License
 * @version  $Id$
 */

require_once 'vfsStream/vfsStream.php';

/**
 * DbSync_Table_AbstractTableTest
 *
 * @group    table
 * @category DbSync
 * @package  Tests
 * @version  $Id$
 */
class DbSync_Table_AbstractTableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $_fileAdapter;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $_dbAdapter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        global $config;

        $this->_dbAdapter = $this->getMock($config['dbAdapter'], array(), array($config['dbParams']));
        $this->_fileAdapter = $this->getMock($config['fileAdapter'], array(), array($config['path']));
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
     * @param array $methods
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function _getMock($methods)
    {
        return $this->getMock(
            'DbSync_Table_Schema',
            $methods,
            array($this->_dbAdapter, $this->_fileAdapter, 'diff')
        );
    }

    /**
     * getTableName
     *
     * @expectedException        DbSync_Exception
     * @expectedExceptionMessage Table name not set
     */
    public function test_getTableName()
    {
        $model = $this->_getMock(null);

        $model->getTableName();
    }

    /**
     * setTableName
     */
    public function test_setTableName()
    {
        $tableName = 'users';

        $model = $this->_getMock(null);

        $model->setTableName($tableName);

        $this->assertEquals($tableName, $model->getTableName());
    }

    /**
     * save
     *
     * @expectedException        DbSync_Exception
     * @expectedExceptionMessage Path 'tablespath' is not writable
     */
    public function test_save_notWriteable()
    {
        $path = 'tablespath';
        $model = $this->_getMock(array('isWriteable'));

        $model->expects($this->atLeastOnce())
              ->method('isWriteable')
              ->will($this->returnValue(false));

        $model->save($path);
    }

    /**
     * save
     *
     */
    public function test_save()
    {
        $path = 'tablespath';
        $data = array('somedata');
        $model = $this->_getMock(array('isWriteable', 'generateConfigData'));

        $model->expects($this->atLeastOnce())
              ->method('isWriteable')
              ->will($this->returnValue(true));

        $model->expects($this->once())
              ->method('generateConfigData')
              ->will($this->returnValue($data));

        $this->_fileAdapter->expects($this->once())
                           ->method('write')
                           ->with($this->equalTo($path), $this->equalTo($data));

        $model->save($path);
    }

    /**
     * hasFile
     *
     */
    public function test_hasFile()
    {
        $filepath = 'somepath';

        $model = $this->_getMock(array('getFilePath'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue($filepath));

        $this->assertTrue($model->hasFile($filepath));
    }

    /**
     * hasFile
     *
     */
    public function test_hasFile_false()
    {
        $filepath = null;

        $model = $this->_getMock(array('getFilePath'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue($filepath));

        $this->assertFalse($model->hasFile($filepath));
    }

    /**
     * getStatus
     *
     */
    public function test_getStatus_false()
    {
        $diff = array('some diff data');

        $model = $this->_getMock(array('diff'));

        $model->expects($this->once())
              ->method('diff')
              ->will($this->returnValue($diff));

        $this->assertFalse($model->getStatus());
    }

    /**
     * getStatus
     *
     */
    public function test_getStatus()
    {
        $diff = array();

        $model = $this->_getMock(array('diff'));

        $model->expects($this->once())
              ->method('diff')
              ->will($this->returnValue($diff));

        $this->assertTrue($model->getStatus());
    }

    /**
     * pull
     *
     */
    public function test_pull()
    {
        $diff = array();

        $model = $this->_getMock(array('init'));

        $model->expects($this->once())
              ->method('init')
              ->with($this->equalTo(true));

        $model->pull();
    }

    /**
     * hasDbTable
     *
     */
    public function test_hasDbTable()
    {
        $tableName = 'users';

        $model = $this->_getMock(array('getTableName'));

        $model->expects($this->any())
              ->method('getTableName')
              ->will($this->returnValue($tableName));

        $this->_dbAdapter->expects($this->once())
                         ->method('hasTable')
                         ->with($this->equalTo($tableName))
                         ->will($this->returnValue(true));

        $this->assertTrue($model->hasDbTable());
    }

    /**
     * hasDbTable
     *
     */
    public function test_hasDbTable_false()
    {
        $tableName = 'users';

        $model = $this->_getMock(array('getTableName'));

        $model->expects($this->any())
              ->method('getTableName')
              ->will($this->returnValue($tableName));

        $this->_dbAdapter->expects($this->once())
                         ->method('hasTable')
                         ->with($this->equalTo($tableName))
                         ->will($this->returnValue(false));

        $this->assertFalse($model->hasDbTable());
    }

    /**
     * getListDb
     *
     */
    public function test_getListDb()
    {
        $list = array('users', 'articles', 'settings');

        $model = $this->_getMock(null);

        $this->_dbAdapter->expects($this->once())
                         ->method('getTableList')
                         ->will($this->returnValue($list));

        $this->assertEquals($list, $model->getListDb());
    }

    /**
     * getListConfig
     *
     */
    public function test_getListConfig()
    {
        $list = array('users', 'articles', 'settings');

        $model = $this->_getMock(null);

        $this->_fileAdapter->expects($this->once())
                           ->method('getTableList')
                           ->will($this->returnValue($list));

        $this->assertEquals($list, $model->getListConfig());
    }

    /**
     * getList
     *
     * @depends test_getListConfig
     * @depends test_getListDb
     */
    public function test_getList()
    {
        $list1 = array('users', 'articles', 'settings');
        $list2 = array('users1', 'articles', 'settings2');
        $list = array_unique(array_merge($list1, $list2));
        sort($list);

        $model = $this->_getMock(array('getListConfig', 'getListDb'));

        $model->expects($this->once())
              ->method('getListConfig')
              ->will($this->returnValue($list1));

        $model->expects($this->once())
              ->method('getListDb')
              ->will($this->returnValue($list2));

        $result = $model->getList();
        sort($result);

        $this->assertEquals($list, $result);
    }

    /**
     * getFilePath
     *
     */
    public function test_getFilePath_notReal()
    {
        $tableName = 'users';
        $path = 'some path';

        $model = $this->_getMock(array('getTableName'));

        $model->expects($this->atLeastOnce())
              ->method('getTableName')
              ->will($this->returnValue($tableName));

        $this->_fileAdapter->expects($this->once())
                           ->method('getFilePath')
                           ->with($this->equalTo($tableName))
                           ->will($this->returnValue($path));

        $this->assertEquals($path, $model->getFilePath(false));
    }

    /**
     * getFilePath
     *
     */
    public function test_getFilePath()
    {
        $path = '.';
        $tableName = 'users';
        $model = $this->_getMock(array('getTableName'));

        $model->expects($this->atLeastOnce())
              ->method('getTableName')
              ->will($this->returnValue($tableName));

        $this->_fileAdapter->expects($this->once())
                           ->method('getFilePath')
                           ->with($this->equalTo($tableName))
                           ->will($this->returnValue($path));

        $this->assertNotNull($model->getFilePath(true));
    }

    /**
     * deleteFile
     *
     * @expectedException        DbSync_Exception
     * @expectedExceptionMessage Config file not found
     */
    public function test_deleteFile_noFile()
    {
        $model = $this->_getMock(array('getFilePath', 'getTableName'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue(false));

        $model->deleteFile();
    }

    /**
     * deleteFile
     *
     * @expectedException        DbSync_Exception
     * @expectedExceptionMessage Config file 'somepath' is not writable
     */
    public function test_deleteFile_notWriteable()
    {
        $filepath = 'somepath';

        $model = $this->_getMock(array('getFilePath', 'isWriteable'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue($filepath));

        $model->expects($this->atLeastOnce())
              ->method('isWriteable')
              ->will($this->returnValue(false));

        $model->deleteFile();
    }

    /**
     * deleteFile
     *
     */
    public function test_deleteFile()
    {
        vfsStream::setup('exampleDir');

        $tableName = 'users';
        $filepath = vfsStream::url('exampleDir/asdfasdf.xml');
        fopen($filepath, 'a');

        $model = $this->_getMock(array('getFilePath', 'isWriteable'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue($filepath));

        $model->expects($this->atLeastOnce())
              ->method('isWriteable')
              ->will($this->returnValue(true));

        $this->assertTrue(file_exists($filepath));

        $this->assertTrue($model->deleteFile());

        $this->assertFalse(file_exists($filepath));
    }

    /**
     * isWriteable
     *
     */
    public function test_isWriteable()
    {
        vfsStream::setup('exampleDir');

        $filepath = vfsStream::url('exampleDir/config.xml');
        fopen($filepath, 'a');

        $model = $this->_getMock(array('getFilePath'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue($filepath));

        $this->assertTrue($model->isWriteable());
    }

    /**
     * isWriteable
     *
     * @depends test_getFilePath_notReal
     * @depends test_getFilePath_notReal
     * @depends test_deleteFile_noFile
     */
    public function test_isWriteable_false()
    {
        vfsStream::setup('exampleDir');
        $filepath = vfsStream::url('exampleDir/tables/config.xml');

        $tableName = 'users';

        $model = $this->_getMock(array('getTableName'));

        $this->_fileAdapter->expects($this->exactly(2))
                           ->method('getFilePath')
                           ->will($this->returnValue($filepath));

        $model->expects($this->atLeastOnce())
              ->method('getTableName')
              ->will($this->returnValue($tableName));

        $this->assertTrue($model->isWriteable());
    }

    /**
     * init
     *
     * @expectedException        DbSync_Exception
     * @expectedExceptionMessage Path 'somepath' is not writable
     */
    public function test_init_notWriteable()
    {
        $filepath = 'somepath';

        $model = $this->_getMock(array('getFilePath', 'isWriteable'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue($filepath));

        $model->expects($this->atLeastOnce())
              ->method('isWriteable')
              ->will($this->returnValue(false));

        $model->init(true);
    }

    /**
     * init
     *
     */
    public function test_init()
    {
        $filepath = 'somepath';

        $model = $this->_getMock(array('getFilePath', 'isWriteable', 'save'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue($filepath));

        $model->expects($this->atLeastOnce())
              ->method('isWriteable')
              ->will($this->returnValue(true));

        $model->expects($this->once())
              ->method('save')
              ->with($this->equalTo($filepath));

        $this->assertTrue($model->init(true));
    }

    /**
     * init
     *
     */
    public function test_init_false()
    {
        $filepath = 'somepath';
        $model = $this->_getMock(array('getFilePath', 'isWriteable', 'save'));

        $model->expects($this->once())
              ->method('getFilePath')
              ->will($this->returnValue($filepath));

        $model->expects($this->never())
              ->method('save');

        $this->assertFalse($model->init());
    }
}


<?php
date_default_timezone_set('UTC');
/**
 * Check phpunit version
 */
if (version_compare(PHPUnit_Runner_Version::id(), '3.6.0RC4', '<')) {
    echo "PHPUnit version 3.6.0RC4 or higher is required", PHP_EOL;
    echo "Current version is " . PHPUnit_Runner_Version::id(), PHP_EOL;
    exit;
}

/**
 * Add path
 */
set_include_path(get_include_path() . PATH_SEPARATOR . dirname(dirname(__FILE__)));

/**
 * Stubbed config
 *
 * @var array
 */
$config = array(
    'dbAdapter' => 'DbSync_Table_DbAdapter_Mysql',
    'fileAdapter' => 'DbSync_Table_FileAdapter_SfYaml',
    'dbParams' => array(
        'host'     => '',
        'dbname'   => 'dbSync',
        'username' => 'dbSync_user',
        'password' => '',
        'options'  => array()
    ),
    'path' => 'tables',
    'diffprog' => 'diff'
);


/**
 * File adapter classes
 */
require_once 'SymfonyComponents/YAML/sfYaml.php';
require_once 'DbSync/Table/FileAdapter/AdapterInterface.php';
require_once 'DbSync/Table/FileAdapter/SfYaml.php';

/**
 * Database adapter classes
 */
require_once 'DbSync/Table/DbAdapter/AdapterInterface.php';
require_once 'DbSync/Table/DbAdapter/Mysql.php';

/**
 * App classes
 */
require_once 'DbSync/Exception.php';
require_once 'DbSync/Console.php';
require_once 'DbSync/Table/AbstractTable.php';
require_once 'DbSync/Controller/AbstractController.php';

require_once 'DbSync/Table/Data.php';
require_once 'DbSync/Controller/DataController.php';

require_once 'DbSync/Table/Trigger.php';
require_once 'DbSync/Controller/TriggerController.php';

require_once 'DbSync/Table/Schema.php';
require_once 'DbSync/Controller/SchemaController.php';
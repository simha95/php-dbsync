<?php
/**
 * DbSync_Controller_Schema
 *
 * @version $Id$
 */
class DbSync_Controller_Schema extends DbSync_Controller
{
    /**
    * @var string
    */
    protected $_modelClass = 'DbSync_Table_Schema';

    /**
     * Help
     *
     * @see DbSync_Controller::help()
     */
    public function helpAction()
    {
        echo "Usage {$this->_console->getProgname()} [action] [ [tableName] ... ] ", PHP_EOL;

        echo PHP_EOL;

        echo $this->colorize("if tableName not specified action applied to all tables/configs"), PHP_EOL;

        echo PHP_EOL;

        echo "Actions:", PHP_EOL;

        echo $this->colorize("init", 'green');
        echo "     Create database schema config in specified path", PHP_EOL;

        echo $this->colorize("status", 'green');
        echo "   Check schema status (Ok/Unsyncronized)", PHP_EOL;

        echo $this->colorize("diff", 'green');
        echo "     Show diff between database table schema and schema config file", PHP_EOL;

        echo $this->colorize("pull", 'green');
        echo "     Override current schema config file by new created from database", PHP_EOL;

        echo $this->colorize("push", 'green');
        echo "     Override database schema by current schema config file", PHP_EOL;

        echo $this->colorize("help", 'green');
        echo "     help message", PHP_EOL;

        echo PHP_EOL;
    }

    /**
     * Push
     *
     */
    public function push()
    {
        $tableName = $this->_model->getTableName();

        if ($this->_model->hasFile()) {
            if ($this->_console->hasOption('show')) {
                echo $this->_model->createAlter();
            } else {
                 $this->_model->push();

                 echo $tableName . $this->colorize(" - Updated", 'green');
            }
        } else {
            echo $tableName . $this->colorize(" - Schema not found", 'red');
        }
        echo PHP_EOL;
    }

    /**
     * Status
     *
     */
    public function status()
    {
        $tableName = $this->_model->getTableName();

        if ($this->_model->hasDbTable() && $this->_model->hasFile()) {
            if ($this->_model->getStatus()) {
                echo $tableName . $this->colorize(" - Ok", 'green');
            } else {
                echo $tableName . $this->colorize(" - Unsyncronized", 'red');
            }
        } else {
            if (!$this->_model->hasDbTable()) {
                echo $tableName . $this->colorize(" - Table not found", 'red');
            } else {
                echo $tableName . $this->colorize(" - Schema not found", 'red');
            }
        }
        echo PHP_EOL;
    }

    /**
     * Init
     *
     */
    public function init()
    {
        $tableName = $this->_model->getTableName();

        if ($this->_model->hasDbTable()) {
            if ($this->_model->hasFile()) {
                echo $tableName . $this->colorize(" - Already has data", 'red');
            } else {
                if ($this->_model->isWriteable()) {
                    $this->_model->init();
                    echo $tableName . $this->colorize(" - Ok", 'green');
                } else {
                    echo $tableName . $this->colorize(" - Path is not writeable", 'red');
                }
            }
        } else {
            echo $tableName . $this->colorize(" - Table not found", 'red');
        }
        echo PHP_EOL;
    }

    /**
     * Pull
     *
     */
    public function pull($tableName = null)
    {
        $tableName = $this->_model->getTableName();

        if ($this->_model->hasDbTable()) {
            if ($this->_model->isWriteable()) {
                $this->_model->pull();
                echo $tableName . $this->colorize(" - Ok", 'green');
            } else {
                echo $tableName . $this->colorize(" - Path is not writeable", 'red');
            }
        } else {
            echo $tableName . $this->colorize(" - Table not found", 'red');
        }
        echo PHP_EOL;
    }

    /**
     * Diff
     *
     */
    public function diff($tableName = null)
    {
        $tableName = $this->_model->getTableName();

        if ($this->_model->hasDbTable() && $this->_model->hasFile()) {
            if (!$this->_model->getStatus()) {
                echo join(PHP_EOL, $this->_model->diff()), PHP_EOL;
            }
        } else {
            if (!$this->_model->hasDbTable()) {
                echo $tableName . $this->colorize(" - Table not found", 'red');
            } else {
                echo $tableName . $this->colorize(" - Schema not found", 'red');
            }
            echo PHP_EOL;
        }
    }
}
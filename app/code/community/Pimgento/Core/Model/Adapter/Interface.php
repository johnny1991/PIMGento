<?php
/**
 * @author    Johnny Cottereau - Teclib' <jcottereau@teclib.com>
 * @copyright Copyright (c) 2015
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Pimgento Database Adapter Interface
 */
interface Pimgento_Core_Model_Adapter_Interface extends Varien_Db_Adapter_Interface
{

    /**
     * Change table auto increment value
     *
     * @param string $tableName
     * @param string $increment
     * @param null|string $schemaName
     * @return Zend_Db_Statement_Interface
     */
    public function changeTableAutoIncrement($tableName, $increment, $schemaName = null);

    /**
     * Inserts a table row with specified data.
     *
     * @param mixed $table The table to insert data into.
     * @param array $bind Column-value pairs.
     * @return int The number of affected rows.
     * @throws Zend_Db_Adapter_Exception
     */
    public function insertIgnore($table, array $bind);

}

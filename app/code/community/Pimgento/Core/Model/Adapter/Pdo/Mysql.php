<?php
/**
 * @author    Johnny Cottereau - Teclib' <jcottereau@teclib.com>
 * @copyright Copyright (c) 2015
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Pimgento Mysql PDO DB adapter
 */
class Pimgento_Core_Model_Adapter_Pdo_Mysql extends Varien_Db_Adapter_Pdo_Mysql
    implements Pimgento_Core_Model_Adapter_Interface
{
    /**
     * Change table auto increment value
     *
     * @param string $tableName
     * @param string $increment
     * @param null|string $schemaName
     * @return Zend_Db_Statement_Interface
     */
    public function changeTableAutoIncrement($tableName, $increment, $schemaName = null)
    {
        $table = $this->quoteIdentifier($this->_getTableName($tableName, $schemaName));
        $sql = sprintf('ALTER TABLE %s AUTO_INCREMENT=%d', $table, $increment);
        return $this->raw_query($sql);
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param mixed $table The table to insert data into.
     * @param array $bind Column-value pairs.
     * @return int The number of affected rows.
     * @throws Zend_Db_Adapter_Exception
     */
    public function insertIgnore($table, array $bind)
    {
        // extract and quote col names from the array keys
        $cols = array();
        $vals = array();
        $i = 0;
        foreach ($bind as $col => $val) {
            $cols[] = $this->quoteIdentifier($col, true);
            if ($val instanceof Zend_Db_Expr) {
                $vals[] = $val->__toString();
                unset($bind[$col]);
            } else {
                if ($this->supportsParameters('positional')) {
                    $vals[] = '?';
                } else {
                    if ($this->supportsParameters('named')) {
                        unset($bind[$col]);
                        $bind[':col' . $i] = $val;
                        $vals[] = ':col' . $i;
                        $i++;
                    } else {
                        /** @see Zend_Db_Adapter_Exception */
                        #require_once 'Zend/Db/Adapter/Exception.php';
                        throw new Zend_Db_Adapter_Exception(
                            get_class($this) . " doesn't support positional or named binding"
                        );
                    }
                }
            }
        }

        // build the statement
        $sql = "INSERT IGNORE INTO "
            . $this->quoteIdentifier($table, true)
            . ' (' . implode(', ', $cols) . ') '
            . 'VALUES (' . implode(', ', $vals) . ')';

        // execute the statement and return the number of affected rows
        if ($this->supportsParameters('positional')) {
            $bind = array_values($bind);
        }
        $stmt = $this->query($sql, $bind);
        $result = $stmt->rowCount();
        return $result;
    }

}

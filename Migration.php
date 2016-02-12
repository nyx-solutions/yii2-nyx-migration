<?php

    namespace nox\migrations\Migration;

    use yii\base\NotSupportedException;
    use yii\db\Connection;

    /**
     * Class Migration
     *
     * @package nox\migrations\Migration
     */
    class Migration extends \yii\db\Migration
    {
        const YES             = 1;
        const NO              = 0;

        const STATUS_ACTIVE   = 1;
        const STATUS_INACTIVE = 0;

        /**
         * @var string
         */
        public $tableName;

        /**
         * @var string
         */
        public $tableOptions;

        /**
         * @param string $table
         *
         * @return bool
         *
         * @throws NotSupportedException
         */
        public function tableExists($table)
        {
            $db     = \Yii::$app->db;
            $schema = $db->getSchema();

            $tables        = $schema->getTableNames();
            $realTableName = $schema->getRawTableName($table);

            if (in_array($realTableName, $tables)) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * @param string $table
         * @param string $column
         *
         * @return bool
         *
         * @throws NotSupportedException
         */
        public function columnExists($table, $column)
        {
            if ($this->tableExists($table)) {
                /** @var Connection $db */
                $db     = \Yii::$app->db;
                $schema = $db->getSchema();

                $columns = $schema->getTableSchema($table)->getColumnNames();

                if (in_array($column, $columns)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        /**
         * @param string $view
         *
         * @return bool
         */
        public function viewExists($view)
        {
            return $this->tableExists($view);
        }

        /**
         * @param string $view
         * @param string $select
         */
        public function createView($view, $select)
        {
            $this->execute("CREATE OR REPLACE VIEW {$view} AS {$select}");
        }

        /**
         * @param string $view
         */
        public function dropView($view)
        {
            if ($this->viewExists($view)) {
                $this->execute("DROP VIEW {$view}");
            }
        }
    }

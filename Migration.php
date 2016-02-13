<?php

    namespace nox\db;

    use yii\base\NotSupportedException;
    use yii\db\Connection;

    /**
     * Class Migration
     *
     * @package nox\migrations\Migration
     */
    class Migration extends \yii\db\Migration
    {
        #region Constants
        const YES             = 1;
        const NO              = 0;

        const STATUS_ACTIVE   = 1;
        const STATUS_INACTIVE = 0;
        #endregion

        /**
         * @var bool
         */
        protected $onlyMySql = false;

        /**
         * @var string
         */
        protected $tableName;

        /**
         * @var string
         */
        protected $tableOptions;

        #region Initialization
        /**
         * @inheritdoc
         *
         * @throws NotSupportedException
         */
        public function init()
        {
            parent::init();

            if ($this->db->driverName === 'mysql') {
                $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
            } else {
                if ((bool)$this->onlyMySql) {
                    throw new NotSupportedException('MySQL required.');
                }
            }
        }
        #endregion

        #region DataBase
        #region DataBase Tables
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
        #endregion

        #region DataBase Views
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
        #endregion

        #region DataBase Table Names
        /**
         * @param string $name
         *
         * @return string
         */
        public function getTableName($name = '')
        {
            return '{{%'.((!empty($name)) ? $name : $this->getSimpleTableName()).'}}';
        }

        /**
         * @param string $name
         *
         * @return string
         */
        public function withTableName($name)
        {
            return '{{%'.$this->getSimpleTableName().'_'.$name.'}}';
        }

        /**
         * @return string
         */
        protected function getSimpleTableName()
        {
            $tableName = (string)$this->tableName;

            $tableName = preg_replace('/^\{\{%/', '', $tableName);
            $tableName = preg_replace('/\}\}$/', '', $tableName);

            return $tableName;
        }
        #endregion
        #endregion
    }

<?php

    namespace nox\db;

    use yii\base\NotSupportedException;
    use yii\db\Connection;
    use yii\db\Expression;

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

        /**
         * @return string
         */
        protected function getTableOptions()
        {
            return $this->tableOptions;
        }
        #endregion

        #region DataBase FKs
        /**
         * @inheritdoc
         */
        public function addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null)
        {
            $indexName = $name;

            if (preg_match('/\}\}$/', $indexName)) {
                $indexName = preg_replace('/^(.*)\}\}$/', '$1_idx}}', $indexName);
            } else {
                $indexName .= '_idx';
            }

            $this->createIndex($indexName, $table, $columns);

            parent::addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);
        }

        /**
         * Builds a SQL statement for adding a foreign key constraint to an existing table (without index creation).
         * The method will properly quote the table and column names.
         * @param string $name the name of the foreign key constraint.
         * @param string $table the table that the foreign key constraint will be added to.
         * @param string|array $columns the name of the column to that the constraint will be added on. If there are multiple columns, separate them with commas or use an array.
         * @param string $refTable the table that the foreign key references to.
         * @param string|array $refColumns the name of the column that the foreign key references to. If there are multiple columns, separate them with commas or use an array.
         * @param string $delete the ON DELETE option. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
         * @param string $update the ON UPDATE option. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
         *
         * @see addForeignKey
         */
        public function addForeignKeyWithoutIndex($name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null)
        {
            parent::addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);
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
         * @return string
         */
        protected function getSimpleTableName()
        {
            $tableName = (string)$this->tableName;

            $tableName = preg_replace('/^\{\{%/', '', $tableName);
            $tableName = preg_replace('/\}\}$/', '', $tableName);

            return $tableName;
        }

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
         * @return string
         */
        public function getCurrentTableName()
        {
            return $this->getTableName();
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
        #endregion
        #endregion

        #region SchemaBuilderTrait
        /**
         * @inheritdoc
         */
        public function boolean()
        {
            $boolean = parent::boolean();

            return $this->smallInteger(1);
        }

        /**
         * @return Expression
         */
        public function now()
        {
            return new Expression('NOW()');
        }
        #endregion
    }

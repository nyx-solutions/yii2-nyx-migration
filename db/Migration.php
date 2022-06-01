<?php

    namespace nyx\db;

    use nyx\helpers\InflectorHelper;
    use Yii;
    use yii\base\NotSupportedException;

    /**
     * Class Migration
     *
     * @package nyx\migrations\Migration
     *
     * @author Jonatas Sas <atendimento@jsas.com.br>
     */
    class Migration extends \yii\db\Migration
    {
        use SchemaBuilderTrait;

        #region Constants
        const BIG_PK_DEFAULT_LENGTH = 20;

        const YES                   = Schema::YES;
        const NO                    = Schema::NO;

        const STATUS_ACTIVE         = Schema::YES;
        const STATUS_INACTIVE       = Schema::NO;

        const ROW_FORMAT_COMPACT    = 'COMPACT';
        const ROW_FORMAT_REDUNDANT  = 'REDUNDANT';
        const ROW_FORMAT_DYNAMIC    = 'DYNAMIC';
        const ROW_FORMAT_COMPRESSED = 'COMPRESSED';

        const NAME_MAX_LENGTH       = 31;
        #endregion

        /**
         * @var bool
         */
        protected bool $onlyMySql = false;

        /**
         * @var string
         */
        protected string $tableName;

        /**
         * @var string
         */
        protected string $tableOptions;

        /**
         * @var string
         */
        public string $tableCharacterSet = 'utf8';

        /**
         * @var string
         */
        public string $tableCollate = 'utf8_unicode_ci';

        /**
         * @var string
         */
        public string $tableEngine = 'InnoDB';

        /**
         * @var bool
         */
        public bool $useMysqlInnoDbRowFormat = true;

        /**
         * @var bool
         */
        public bool $useMysqlInnoDbBarracudaFileFormat = false;

        /**
         * @var string
         */
        public string $mysqlInnoDbRowFormat = self::ROW_FORMAT_DYNAMIC;

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
                $rowFormat = '';

                if ($this->useMysqlInnoDbRowFormat && strtolower($this->tableEngine) == 'innodb') {
                    $rowFormat = " ROW_FORMAT={$this->mysqlInnoDbRowFormat}";
                }

                if ($this->useMysqlInnoDbRowFormat && $this->useMysqlInnoDbBarracudaFileFormat && strtolower($this->tableEngine) == 'innodb') {
                    $rowFormat = " ROW_FORMAT=".self::ROW_FORMAT_COMPRESSED;
                }

                $this->tableOptions = "CHARACTER SET {$this->tableCharacterSet} COLLATE {$this->tableCollate}{$rowFormat} ENGINE={$this->tableEngine}";
            } else {
                if ((bool)$this->onlyMySql) {
                    throw new NotSupportedException('MySQL required.');
                }
            }
        }
        #endregion

        #region DataBase
        /**
         * @inheritdoc
         */
        public function safeDown()
        {
            $this->dropTable($this->getCurrentTableName());
        }

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
            $db     = Yii::$app->db;
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
                $db = Yii::$app->db;

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
         * @param string $table
         * @param string $column
         *
         * @return bool
         *
         * @throws NotSupportedException
         */
        public function fieldExists($table, $column)
        {
            return $this->columnExists($table, $column);
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
         *
         * @noinspection RegExpRedundantEscape
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
         * Builds a SQL statement for adding a foreign key constraint to an existing table.
         * The method will properly quote the table and column names.
         *
         * @param string       $name       the name of the foreign key constraint.
         * @param string       $table      the table that the foreign key constraint will be added to.
         * @param string|array $columns    the name of the column to that the constraint will be added on. If there are multiple columns, separate them with commas or use an array.
         * @param string       $refTable   the table that the foreign key references to.
         * @param string|array $refColumns the name of the column that the foreign key references to. If there are multiple columns, separate them with commas or use an array.
         * @param string       $delete     the ON DELETE option. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
         * @param string       $update     the ON UPDATE option. Most DBMS support these options: RESTRICT, CASCADE, NO ACTION, SET DEFAULT, SET NULL
         *
         * @noinspection RegExpRedundantEscape
         */
        public function addUniqueForeignKey($name, $table, $columns, $refTable, $refColumns, $delete = null, $update = null)
        {
            $indexName = $name;

            if (preg_match('/\}\}$/', $indexName)) {
                $indexName = preg_replace('/^(.*)\}\}$/', '$1_idx}}', $indexName);
            } else {
                $indexName .= '_idx';
            }

            $this->createIndex($indexName, $table, $columns, true);

            parent::addForeignKey($name, $table, $columns, $refTable, $refColumns, $delete, $update);
        }

        /**
         * @inheritdoc
         *
         * @noinspection RegExpRedundantEscape
         */
        public function dropForeignKey($name, $table)
        {
            $indexName = $name;

            if (preg_match('/\}\}$/', $indexName)) {
                $indexName = preg_replace('/^(.*)\}\}$/', '$1_idx}}', $indexName);
            } else {
                $indexName .= '_idx';
            }

            parent::dropForeignKey($name, $table);

            $this->dropIndex($indexName, $table);
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
         *
         * @throws NotSupportedException
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
         *
         * @throws NotSupportedException
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
         *
         * @noinspection RegExpRedundantEscape
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
         * @return string
         */
        public function findCurrentTableName()
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

        /**
         * @param string $name
         *
         * @return string
         */
        public function getFieldName($name)
        {
            $tableName            = InflectorHelper::camel2id(((!empty($this->db->tablePrefix)) ? "{$this->db->tablePrefix}_" : '').$this->getSimpleTableName(), '_');
            $tableNameInitials    = '';
            $tableNameInitialsAux = explode('_', $tableName);

            foreach ($tableNameInitialsAux as $initial) {
                $tableNameInitials .= $initial;
            }

            $fieldName = InflectorHelper::camel2id($name, '_');

            return "{$tableNameInitials}_{$fieldName}";
        }
        #endregion
        #endregion
    }

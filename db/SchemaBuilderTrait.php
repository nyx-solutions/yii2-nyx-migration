<?php

    namespace nox\db;

    use yii\db\ColumnSchemaBuilder;
    use yii\db\Expression;

    /**
     * Class SchemaBuilderTrait
     *
     * @author Jonatas Sas <atendimento@jsas.com.br>
     */
    trait SchemaBuilderTrait
    {
        use \yii\db\SchemaBuilderTrait;

        /**
         * @return \yii\db\Connection the database connection to be used for schema building.
         */
        protected abstract function getDb();

        /**
         * Creates a medium text column.
         * @return ColumnSchemaBuilder the column instance which can be further customized.
         */
        public function mediumText()
        {
            return $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext');
        }

        /**
         * Creates a long text column.
         * @return ColumnSchemaBuilder the column instance which can be further customized.
         */
        public function longText()
        {
            return $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext');
        }

        /**
         * Creates a tiny text column.
         * @return ColumnSchemaBuilder the column instance which can be further customized.
         */
        public function tinyText()
        {
            return $this->getDb()->getSchema()->createColumnSchemaBuilder('tinytext');
        }

        /**
         * @param bool $true
         *
         * @return ColumnSchemaBuilder the column instance which can be further customized.
         */
        public function smallIntegerBoolean($true = true)
        {
            return $this->smallInteger(1)->unsigned()->notNull()->defaultValue((((bool)$true) ? 1 : 0));
        }

        /**
         * @return Expression
         */
        public function now()
        {
            return new Expression('NOW()');
        }
    }

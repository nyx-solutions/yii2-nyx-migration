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
         * @return ColumnSchemaBuilder the column instance which can be further customized.
         */
        public function tinyText()
        {
            return $this->string(255);
        }

        /**
         * @return ColumnSchemaBuilder the column instance which can be further customized.
         */
        public function mediumText()
        {
            if ($this->getDb()->driverName == 'mysql') {
                return $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext');
            }

            return $this->text();
        }

        /**
         * @return ColumnSchemaBuilder the column instance which can be further customized.
         */
        public function longText()
        {
            if ($this->getDb()->driverName == 'mysql') {
                return $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext');
            }

            return $this->text();
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

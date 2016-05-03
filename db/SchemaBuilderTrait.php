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

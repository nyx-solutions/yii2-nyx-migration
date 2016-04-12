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
         * @return ColumnSchemaBuilder
         */
        public function boolean()
        {
            return $this->smallInteger(1)->unsigned();
        }

        /**
         * @return Expression
         */
        public function now()
        {
            return new Expression('NOW()');
        }
    }

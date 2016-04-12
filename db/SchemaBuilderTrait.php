<?php

    namespace nox\db;

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
         * @inheritdoc
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

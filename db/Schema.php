<?php

    namespace nox\db;

    /**
     * Class Schema
     *
     * @package nox\db
     *
     * @author Jonatas Sas <atendimento@jsas.com.br>
     */
    abstract class Schema extends \yii\db\Schema
    {
        #region Constants
        const YES = 1;
        const NO  = 0;
        #endregion
    }

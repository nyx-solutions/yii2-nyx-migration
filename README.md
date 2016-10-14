Yii PHP Framework Version 2 / NOX Migration Library
===================================================

Yii2 NOX Migration is a library with Migrations methods for Yii2. 

[![Latest Stable Version](https://poser.pugx.org/nox-it/yii2-nox-migration/v/stable)](https://packagist.org/packages/nox-it/yii2-nox-migration)
[![Total Downloads](https://poser.pugx.org/nox-it/yii2-nox-migration/downloads)](https://packagist.org/packages/nox-it/yii2-nox-migration)
[![Latest Unstable Version](https://poser.pugx.org/nox-it/yii2-nox-migration/v/unstable)](https://packagist.org/packages/nox-it/yii2-nox-migration)
[![License](https://poser.pugx.org/nox-it/yii2-nox-migration/license)](https://packagist.org/packages/nox-it/yii2-nox-migration)
[![Monthly Downloads](https://poser.pugx.org/nox-it/yii2-nox-migration/d/monthly)](https://packagist.org/packages/nox-it/yii2-nox-migration)
[![Daily Downloads](https://poser.pugx.org/nox-it/yii2-nox-migration/d/daily)](https://packagist.org/packages/nox-it/yii2-nox-migration)
[![composer.lock](https://poser.pugx.org/nox-it/yii2-nox-migration/composerlock)](https://packagist.org/packages/nox-it/yii2-nox-migration)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
php composer.phar require --prefer-dist nox-it/yii2-nox-migration "*"
```

or add

```
"nox-it/yii2-nox-migration": "*"
```

to the require section of your `composer.json` file.

## Usage

```php
use nox\db\Migration;

class m150409_195340_site extends Migration
{
    /**
     * @inheritdoc
     */
    protected $tableName = 'site';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $columns = [
            'id'                => $this->bigPrimaryKey($this->pkLength),
            'name'              => $this->string(2000)->notNull()
        ];

        $this->createTable($this->getCurrentTableName(), $columns, $this->getTableOptions());
    }
}
```

## License

**yii2-nox-migration** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.

![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)

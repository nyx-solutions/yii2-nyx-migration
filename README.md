Yii PHP Framework Version 2 / NYX Migration Library
===================================================

Yii2 NYX Migration is a library with Migrations methods for Yii2. 

[![Latest Stable Version](https://poser.pugx.org/nyx-solutions/yii2-nyx-migration/v/stable)](https://packagist.org/packages/nyx-solutions/yii2-nyx-migration)
[![Total Downloads](https://poser.pugx.org/nyx-solutions/yii2-nyx-migration/downloads)](https://packagist.org/packages/nyx-solutions/yii2-nyx-migration)
[![Latest Unstable Version](https://poser.pugx.org/nyx-solutions/yii2-nyx-migration/v/unstable)](https://packagist.org/packages/nyx-solutions/yii2-nyx-migration)
[![License](https://poser.pugx.org/nyx-solutions/yii2-nyx-migration/license)](https://packagist.org/packages/nyx-solutions/yii2-nyx-migration)
[![Monthly Downloads](https://poser.pugx.org/nyx-solutions/yii2-nyx-migration/d/monthly)](https://packagist.org/packages/nyx-solutions/yii2-nyx-migration)
[![Daily Downloads](https://poser.pugx.org/nyx-solutions/yii2-nyx-migration/d/daily)](https://packagist.org/packages/nyx-solutions/yii2-nyx-migration)
[![composer.lock](https://poser.pugx.org/nyx-solutions/yii2-nyx-migration/composerlock)](https://packagist.org/packages/nyx-solutions/yii2-nyx-migration)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
php composer.phar require --prefer-dist nyx-solutions/yii2-nyx-migration "*"
```

or add

```
"nyx-solutions/yii2-nyx-migration": "*"
```

to the require section of your `composer.json` file.

## Usage

```php
use nyx\db\Migration;

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

**yii2-nyx-migration** is released under the BSD 3-Clause License. See the bundled `LICENSE.md` for details.

![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)

## 引入 dotenv

在 `composer.json` 文件中的 `require` 中添加下面一行

```shell script
"yiithings/yii2-dotenv": "*",
```

然后执行

```shell script
composer update
```

## 配置 DB

1. 在根目录下创建 `.env` 文件

2. `.env` 文件中引入默认数据库配置

```shell script
DB_HOST=localhost
DB_DATABASE=local_migration
DB_USERNAME=root
DB_PASSWORD=
```

修改 `config` 目录下的 `db.php` 文件

```shell script
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
```

## 配置 MySQL Migrate

1. `.env` 文件中引入 notes 数据库配置

```shell script
# Notes
NotesMysqlHost=localhost
NotesMysqlDatabase=dev_notes
NotesMysqlUsername=root
NotesMysqlPassword=
```

2. `config` 目录中添加一个 `notes_mysql.php` 的文件，添加MySQL配置

```shell script
<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=" . env('NotesMysqlHost') . ";dbname=" . env('NotesMysqlDatabase'),
    'username' => env('NotesMysqlUsername'),
    'password' => env('NotesMysqlPassword'),
    'charset' => 'utf8mb4',
    'tablePrefix' => env('NotesMysqlPrefix', 'cat_'),
];
```

3. `config/console.php` 文件引入 notes mysql 配置

```shell script
$notes_mysql = require __DIR__ . '/notes_mysql.php';

'campaign_mysql' => $campaign_mysql,
```

4. `aliases` 中添加下面一行

```shell script
'@modules'   => '@app/modules',
```

4. 打开 gii 工具创建 moduel

```shell script
http://local.migration.tool.com/index.php?r=gii
```

5. 创建Mysql数据库变更文件

创建数据库变更操作必须制定目录路径,因为该工具会自动生成对应的代码模板, 指定当前模块使用的mysql数据库, 示例如下

campaign 模块创建数据库变更文件

```shell script
./yii migrate-module/create 'modules\notes\migrations\createNotes1Table'
```



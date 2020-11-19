<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

# notes
$notes_mysql = require __DIR__ . '/notes_mysql.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
        '@modules' => '@app/modules',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        //  MySQL
        'notes_mysql' => $notes_mysql,

        //PgSQL
        //Redis
    ],
    'params' => $params,
    'controllerMap' => [
        'migrate' => [
            'class'=>'app\controllers\ApiController'
        ],
        // Migrations for the specific project's module
        'migrate-module' => [
            'class' => 'app\controllers\MigrateController',
            'migrationNamespaces' => [
                'modules\notes\migrations',
            ],
            'migrationTable' => 'migration_module',
            'migrationPath' => null,
        ],
        'migrate-pgsql' => [
            'class' => 'app\components\pgsql\migration\MigrateController',
            'migrationNamespaces' => [
                'modules\content\pgsql_migrations',
            ],
            'migrationTable' => 'migration_pgsql',
            'migrationPath' => null,
        ],
        // Migrations for the specific extension
        'migrate-rbac' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => 'rbac/migrations',
            'migrationTable' => 'migration_rbac',
        ],
//        // Migrations for the specific extension
        'migrate-elasticsearch' => [
            'class' => 'app\components\elasticsearch\migration\MigrateController',
            'migrationPath' => 'elasticsearch/migrations',
            'migrationTable' => 'migration_elasticsearch',
        ],
        'migrate-createCommandUser' => [
            'class' => 'app\controllers\CreateMicroServiceCommandUserController',
        ],
        'migrate-createLeadsH5User' => [
            'class' => 'app\controllers\CreateLeadsH5UserController',
        ],
    ],
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

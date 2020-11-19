<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => "mysql:host=" . env('NotesMysqlHost') . ";dbname=" . env('NotesMysqlDatabase'),
    'username' => env('NotesMysqlUsername'),
    'password' => env('NotesMysqlPassword'),
    'charset' => 'utf8mb4',
    'tablePrefix' => env('NotesMysqlPrefix', 'cat_'),
];

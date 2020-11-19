<?php

namespace app\components\pgsql\migration;

class MigrateController extends \app\controllers\MigrateController
{
	public $generatorTemplateFiles = [
		'create_table' => '@app/components/pgsql/migration/templates/createTableMigration.php',
		'drop_table' => '@app/components/pgsql/migration/templates/dropTableMigration.php',
		'add_column' => '@app/components/pgsql/migration/templates/addColumnMigration.php',
		'drop_column' => '@app/components/pgsql/migration/templates/dropColumnMigration.php',
		'create_junction' => '@app/components/pgsql/migration/templates/createTableMigration.php',
	];

	public $templateFile = '@app/components/pgsql/migration/templates/migration.php';
}

<?php

namespace app\controllers;

class MigrateController extends \yii\console\controllers\MigrateController
{
//	public $generatorTemplateFiles = [
//		'create_table' => '@app/controllers/migrate/views/createTableMigration.php',
//		'drop_table' => '@app/controllers/migrate/views/dropTableMigration.php',
//		'add_column' => '@app/controllers/migrate/views/addColumnMigration.php',
//		'drop_column' => '@app/controllers/migrate/views/dropColumnMigration.php',
//		'create_junction' => '@app/controllers/migrate/views/createTableMigration.php',
//	];
//	public $templateFile = '@app/controllers/migrate/views/migration.php';

    public $generatorTemplateFiles = [
        'create_table' => '@app/views/migrate/createTableMigration.php',
        'drop_table' => '@app/views/migrate/dropTableMigration.php',
        'add_column' => '@app/views/migrate/addColumnMigration.php',
        'drop_column' => '@app/views/migrate/dropColumnMigration.php',
        'create_junction' => '@app/views/migrate/createTableMigration.php',
    ];
    public $templateFile = '@app/views/migrate/migration.php';

	private function getNamespacePath($namespace)
	{
		return str_replace('/', DIRECTORY_SEPARATOR, \Yii::getAlias('@' . str_replace('\\', '/', $namespace)));
	}

	protected function getNewMigrations()
	{
		$applied = [];
		foreach ($this->getMigrationHistory(null) as $class => $time) {
			$applied[trim($class, '\\')] = true;
		}

		$migrationPaths = [];

		if($this->migrationPath && $this->migrationNamespaces){
			foreach ($this->migrationNamespaces as $namespace) {
				$namespace = str_replace("/", "\\", $namespace);
				$migrationPaths[] = [$this->getNamespacePath($namespace), $namespace];
			}
		}else{
			if (is_array($this->migrationPath)) {
				foreach ($this->migrationPath as $path) {
					$migrationPaths[] = [$path, ''];
				}
			} elseif (!empty($this->migrationPath)) {
				$migrationPaths[] = [$this->migrationPath, ''];
			}
			foreach ($this->migrationNamespaces as $namespace) {
				$migrationPaths[] = [$this->getNamespacePath($namespace), $namespace];
			}
		}


		$migrations = [];
		foreach ($migrationPaths as $item) {
			list($migrationPath, $namespace) = $item;
			if (!file_exists($migrationPath)) {
				continue;
			}
			$handle = opendir($migrationPath);
			while (($file = readdir($handle)) !== false) {
				if ($file === '.' || $file === '..') {
					continue;
				}
				$path = $migrationPath . DIRECTORY_SEPARATOR . $file;
				if (preg_match('/^(m(\d{6}_?\d{6})\D.*?)\.php$/is', $file, $matches) && is_file($path)) {
					$class = $matches[1];
					if (!empty($namespace)) {
						$class = $namespace . '\\' . $class;
					}
					$time = str_replace('_', '', $matches[2]);
					if (!isset($applied[$class])) {
						$migrations[$time . '\\' . $class] = $class;
					}
				}
			}
			closedir($handle);
		}
		ksort($migrations);
		return array_values($migrations);
	}
}
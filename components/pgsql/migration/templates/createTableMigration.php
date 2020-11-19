<?php
/**
 * This view is used by console/controllers/MigrateController.php.
 *
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name without namespace */
/* @var $namespace string the new migration class namespace */
/* @var $table string the name table */
/* @var $tableComment string the comment table */
/* @var $fields array the fields */
/* @var $foreignKeys array the foreign keys */

echo "<?php\n";
if (!empty($namespace)) {
    echo "\nnamespace {$namespace};\n";
}

$namespaceWithModuleName = false;
$moduleName = $namespace;
if ((strlen($namespace) >= strlen('modules')) &&
    (substr($namespace, 0, strlen('modules')) === 'modules') &&
    (strlen($namespace) >= strlen('pgsql_migrations')) &&
    (substr($namespace, -strlen('pgsql_migrations') === 'pgsql_migrations'))
) {
    $moduleName = substr($namespace, strlen('modules'), -strlen('pgsql_migrations'));
    $namespaceWithModuleName = true;
}
$moduleName = trim($moduleName, "\/");
?>

use yii\db\Migration;

/**
 * Handles the creation of table `<?= $table ?>`.
<?= $this->render('_foreignTables', [
    'foreignKeys' => $foreignKeys,
]) ?>
 */
class <?= $className ?> extends Migration implements \app\components\sharding\Sharding
{
    use \app\components\sharding\Migration;

    public function init()
    {
        //指定当前模块使用的pgsql数据库连接信息
<?php if ($namespaceWithModuleName) : ?>
        $this->db = "<?= $moduleName ?>_pgsql";
<?php endif; ?>
        parent::init();
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
<?= $this->render('_createTable', [
    'table' => $table,
    'fields' => $fields,
    'foreignKeys' => $foreignKeys,
])
?>
<?php if (!empty($tableComment)) {
    echo $this->render('_addComments', [
        'table' => $table,
        'tableComment' => $tableComment,
    ]);
}
?>
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
<?= $this->render('_dropTable', [
    'table' => $table,
    'foreignKeys' => $foreignKeys,
])
?>
    }
}

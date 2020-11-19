<?php
/**
 * This view is used by console/controllers/MigrateController.php.
 *
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name without namespace */
/* @var $namespace string the new migration class namespace */
/* @var $table string the name table */
/* @var $fields array the fields */
preg_match('/^drop_(.+)_columns?_from_(.+)_table$/', $name, $matches);
$columns = $matches[1];

echo "<?php\n";
if (!empty($namespace)) {
    echo "\nnamespace {$namespace};\n";
}
$moduleName = str_replace('modules',"",$namespace);
$moduleName = str_replace('migrations',"",$moduleName);
$moduleName = trim($moduleName, "\/");
?>

use yii\db\Migration;

/**
 * Handles dropping <?= $columns ?> from table `<?= $table ?>`.
<?= $this->render('_foreignTables', [
    'foreignKeys' => $foreignKeys,
]) ?>
 */
class <?= $className ?> extends Migration implements \app\components\sharding\Sharding
{
    use \app\components\sharding\Migration;

    public function init()
    {
        //指定当前模块使用的mysql数据库连接信息
        $this->db = "<?= $moduleName ?>_mysql";
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
<?= $this->render('_dropColumns', [
    'table' => $table,
    'fields' => $fields,
    'foreignKeys' => $foreignKeys,
])
?>
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
<?= $this->render('_addColumns', [
    'table' => $table,
    'fields' => $fields,
    'foreignKeys' => $foreignKeys,
])
?>
    }
}

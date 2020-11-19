<?php
/**
 * This view is used by console/controllers/MigrateController.php.
 *
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name without namespace */
/* @var $namespace string the new migration class namespace */

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
 * Class <?= $className . "\n" ?>
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

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "<?= $className ?> cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "<?= $className ?> cannot be reverted.\n";

        return false;
    }
    */
}

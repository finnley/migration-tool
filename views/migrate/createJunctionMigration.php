<?php
/**
 * This view is used by console/controllers/MigrateController.php.
 *
 * The following variables are available in this view:
 * @since 2.0.7
 * @deprecated since 2.0.8
 */
/* @var $className string the new migration class name without namespace */
/* @var $namespace string the new migration class namespace */
/* @var $table string the name table */
/* @var $field_first string the name field first */
/* @var $field_second string the name field second */

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
 * Handles the creation of table `<?= $table ?>` which is a junction between
 * table `<?= $field_first ?>` and table `<?= $field_second ?>`.
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
        $this->createTable('<?= $table ?>', [
            '<?= $field_first ?>_id' => $this->integer(),
            '<?= $field_second ?>_id' => $this->integer(),
            'PRIMARY KEY(<?= $field_first ?>_id, <?= $field_second ?>_id)',
        ]);

        $this->createIndex(
            'idx-<?= $table . '-' . $field_first ?>_id',
            '<?= $table ?>',
            '<?= $field_first ?>_id'
        );

        $this->createIndex(
            'idx-<?= $table . '-' . $field_second ?>_id',
            '<?= $table ?>',
            '<?= $field_second ?>_id'
        );

        $this->addForeignKey(
            'fk-<?= $table . '-' . $field_first ?>_id',
            '<?= $table ?>',
            '<?= $field_first ?>_id',
            '<?= $field_first ?>',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-<?= $table . '-' . $field_second ?>_id',
            '<?= $table ?>',
            '<?= $field_second ?>_id',
            '<?= $field_second ?>',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('<?= $table ?>');
    }
}

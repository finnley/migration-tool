<?php

namespace modules\notes\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class M201125142919CreateCategoryTable extends Migration implements \app\components\sharding\Sharding
{
    use \app\components\sharding\Migration;

    const TABLE_NAME = '{{%category}}';

    public function init()
    {
        //指定当前模块使用的mysql数据库连接信息
        $this->db = 'notes_mysql';
        parent::init();
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB COMMENT="分类"';
        $this->createTable(self::TABLE_NAME, [
            //长度22，适当预留
            'uuid' => $this->char(36)->notNull()->comment('主键ID'),
            'module_uuid' => $this->char(36)->notNull()->comment('模块ID'),
            'category_name' => 'VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT "" COMMENT "分类名称"',
            'number' => $this->integer(11)->unsigned()->notNull()->defaultValue(0)->comment('数量'),
            'sort' => $this->tinyInteger(4)->unsigned()->notNull()->defaultValue(0)->comment('排序'),
            'state' => $this->tinyInteger(4)->unsigned()->notNull()->defaultValue(1)->comment('状态，0-关闭 1-启用'),
            'gmt_create' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('创建时间'),
            'gmt_modified' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('更新时间'),
            'deleted_at' => $this->dateTime()->comment('软删除时间')->defaultValue(null)->null(),
        ], $tableOptions);
        $this->addPrimaryKey('pk_uuid', self::TABLE_NAME, 'uuid');
        $this->createIndex('idx_module_uuid', self::TABLE_NAME, 'module_uuid');
        $this->createIndex('idx_category_name', self::TABLE_NAME, array('category_name'));
        $this->createIndex('idx_sort', self::TABLE_NAME, array('sort'));
        $this->createIndex('idx_state', self::TABLE_NAME, array('state'));
        $this->createIndex('idx_deleted_at', self::TABLE_NAME, array('deleted_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

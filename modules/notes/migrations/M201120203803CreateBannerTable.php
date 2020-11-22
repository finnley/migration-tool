<?php

namespace modules\notes\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%banner}}`.
 */
class M201120203803CreateBannerTable extends Migration implements \app\components\sharding\Sharding
{
    use \app\components\sharding\Migration;

    const TABLE_NAME = '{{%banner}}';

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
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB COMMENT="banner"';
        $this->createTable(self::TABLE_NAME, [
            //长度22，适当预留
            'uuid' => $this->char(36)->notNull()->comment('主键ID'),
            'name' => 'varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT "" COMMENT "Banner名称"',
            'description' => 'varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT "" COMMENT "Banner描述"',
            'gmt_create' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('创建时间'),
            'gmt_modified' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('更新时间'),
            'deleted_at' => $this->dateTime()->comment('软删除时间')->defaultValue(null)->null(),
        ], $tableOptions);
        $this->addPrimaryKey('pk_uuid', self::TABLE_NAME, 'uuid');
        $this->createIndex('idx_deleted_at', self::TABLE_NAME, array('deleted_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

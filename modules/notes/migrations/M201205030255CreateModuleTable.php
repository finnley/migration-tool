<?php

namespace modules\notes\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%module}}`.
 */
class M201205030255CreateModuleTable extends Migration implements \app\components\sharding\Sharding
{
    use \app\components\sharding\Migration;

    const TABLE_NAME = '{{%module}}';

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
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB COMMENT="模块"';
        $this->createTable(self::TABLE_NAME, [
            //长度22，适当预留
            'uuid' => $this->char(36)->notNull()->comment('主键ID'),
            'name' => 'VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT "" COMMENT "模块名称"',
            'english_name' => 'VARCHAR(255) NOT NULL DEFAULT "" COMMENT "英文名称"',
            'description' => 'VARCHAR(255) NOT NULL DEFAULT "" COMMENT "功能介绍"',
            'english_description' => 'VARCHAR(255)  NOT NULL DEFAULT "" COMMENT "英文介绍"',
            'icon' => 'VARCHAR(255) NOT NULL DEFAULT "" COMMENT "image path,icon"',
            'cover_image' => 'VARCHAR(255) NOT NULL DEFAULT "" COMMENT "封面图"',
            'new_feature_deadline' => $this->timestamp()->notNull()->comment('新模块截止日期'),
            'landing_page_url' => 'VARCHAR(255) NOT NULL DEFAULT "" COMMENT "跳转页面 url"',
            'state' => $this->tinyInteger(4)->unsigned()->notNull()->defaultValue(1)->comment('状态，0-关闭 1-启用'),
            'sort' => $this->integer(10)->unsigned()->defaultValue(0)->notNull()->comment('排序'),
            'gmt_create' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('创建时间'),
            'gmt_modified' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('更新时间'),
            'deleted_at' => $this->dateTime()->comment('软删除时间')->defaultValue(null)->null(),
        ], $tableOptions);
        $this->addPrimaryKey('pk_uuid', self::TABLE_NAME, 'uuid');
        $this->createIndex('idx_deleted_at', self::TABLE_NAME, array('deleted_at'));
        $this->createIndex('idx_name', self::TABLE_NAME, array('name'));
        $this->createIndex('idx_state', self::TABLE_NAME, array('state'));
        $this->createIndex('idx_sort', self::TABLE_NAME, array('sort'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

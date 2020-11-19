<?php

namespace modules\notes\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%note_category}}`.
 */
class M201119055823CreateNoteCategoryTable extends Migration implements \app\components\sharding\Sharding
{
    use \app\components\sharding\Migration;

    const TABLE_NAME = '{{%note_category}}';

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
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB COMMENT="Note 分类"';
        $this->createTable(self::TABLE_NAME, [
            //长度22，适当预留
            'uuid' => $this->char(36)->notNull()->comment('主键ID'),
            'category_name' => 'VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT "" COMMENT "分类名称"',
            'start_time' => $this->integer(10)->unsigned()->defaultValue(0)->notNull()->comment('开始时间'),
            'end_time' =>$this->integer(10)->unsigned()->defaultValue(0)->notNull()->comment(' 结束时间'),
            'status' => $this->integer(10)->unsigned()->notNull()->defaultValue(0)
                ->comment('状态，0-关闭 1-正常'),
            'type' => $this->integer(10)->unsigned()->notNull()->defaultValue(0)
                ->comment('类型 0-公众号吸粉 1-表单 2-会议'),
            'mission' => 'json(1024) NULL COMMENT "json格式 任务阶级"',
            'notification' => 'json(1024) NULL  COMMENT "json格式 文案提醒"',
            'poster' => 'json(1024) NULL  COMMENT "json格式 海报"',

//            "gmt_create" => "DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间'",
//            "update_time" => "DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '更新时间'",

            'gmt_create' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('创建时间'),
            'updated_timestamp' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
                ->notNull()->comment('更新时间'),
            'deleted_at' => $this->timestamp()->comment('软删除时间')->defaultValue(null)->null(),
        ], $tableOptions);
        $this->addPrimaryKey('fission_pk', self::TABLE_NAME, 'jing_uuid');
        $this->createIndex('name', self::TABLE_NAME, array('name'));
        $this->createIndex('status', self::TABLE_NAME, array('status'));
        $this->createIndex('type', self::TABLE_NAME, array('type'));
        $this->createIndex('start_time', self::TABLE_NAME, array('start_time'));
        $this->createIndex('end_time', self::TABLE_NAME, array('end_time'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

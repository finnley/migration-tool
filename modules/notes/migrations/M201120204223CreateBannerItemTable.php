<?php

namespace modules\notes\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%banner_item}}`.
 */
class M201120204223CreateBannerItemTable extends Migration implements \app\components\sharding\Sharding
{
    use \app\components\sharding\Migration;

    const TABLE_NAME = '{{%banner_item}}';

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
        $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ENGINE=InnoDB COMMENT="banner子项"';
        $this->createTable(self::TABLE_NAME, [
            //长度22，适当预留
            'uuid' => $this->char(36)->notNull()->comment('主键ID'),
            'banner_uuid' => $this->char(36)->notNull()->comment('banner id'),
            'image_uuid' => $this->char(36)->notNull()->comment('image id'),
            'type' => $this->tinyInteger(4)->unsigned()->notNull()->defaultValue(1)->comment('跳转类型，可能导向商品，可能导向专题，可能导向其他。0，无导向；1：导向商品;2:导向专题'),
            'keyword' => 'varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT "执行关键字，根据不同的type含义不同。比如跳转商品，保存的可能是商品ID"',
            'gmt_create' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull()->comment('创建时间'),
            'gmt_modified' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')->notNull()->comment('更新时间'),
            'deleted_at' => $this->dateTime()->comment('软删除时间')->defaultValue(null)->null(),
        ], $tableOptions);
        $this->addPrimaryKey('pk_uuid', self::TABLE_NAME, 'uuid');
        $this->createIndex('idx_banner_uuid', self::TABLE_NAME, array('banner_uuid'));
        $this->createIndex('idx_image_uuid', self::TABLE_NAME, array('image_uuid'));
        $this->createIndex('idx_type', self::TABLE_NAME, array('type'));
        $this->createIndex('idx_deleted_at', self::TABLE_NAME, array('deleted_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

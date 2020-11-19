<?php

namespace modules\notes\migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notes2}}`.
 */
class M201119053202CreateNotes2Table extends Migration implements \app\components\sharding\Sharding
{
    use \app\components\sharding\Migration;

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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}

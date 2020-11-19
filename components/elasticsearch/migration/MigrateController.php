<?php

namespace app\components\elasticsearch\migration;

class MigrateController extends \yii\console\controllers\MigrateController
{
    public $templateFile = '@app/components/elasticsearch/migration/templates/migration.php';
}

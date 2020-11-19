<?php

namespace app\components\elasticsearch\migration;

use Elasticsearch\Client;

class Migration extends \yii\db\Migration
{
    public $esConnection = 'elasticsearch';

    public function up()
    {
        $this->safeUp();
        return null;
    }

    public function down()
    {
        $this->safeDown();
        return null;
    }

    /**
     * @return Client
     */
    public function esClientConnection($esConnection)
    {
        return \Yii::$app->{$esConnection}->client();
    }

    /**
 * @return Client
 */
    protected function esClient()
    {
        $connection = $this->esConnection;
        return \Yii::$app->{$connection}->client();
    }

    protected function esPutMapping($index, $body, $type = null)
    {
        $client = $this->esClient();
        $indices = $client->indices();

        //Create index
        if (!$indices->exists(['index' => $index])) {
            $indices->create([
                'index' => $index,
            ]);
        }

        //Put mapping
        $params = [
            'index' => $index,
            'body' => $body,
        ];
        if (!is_null($type)) {
            $params['type'] = $type;
        }
        return $indices->putMapping($params);
    }

    protected function esPutTemplate($params)
    {
        $client = $this->esClient();
        $indices = $client->indices();
        try {
            $result = $indices->putTemplate($params);
            return $result;
        } catch (\Exception $e) {
            var_dump("Errors: " . $e->getMessage());
        }
        return false;
    }

    protected function esPutSettings($params)
    {
        $client = $this->esClient();
        $indices = $client->indices();
        try {
            $result = $indices->putSettings($params);
            return $result;
        } catch (\Exception $e) {
            var_dump("Errors: " . $e->getMessage() . $e->getTraceAsString());
        }
        return false;
    }
}

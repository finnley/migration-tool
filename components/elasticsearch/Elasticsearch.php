<?php

namespace app\components\elasticsearch;

use Elasticsearch\Client;
use yii\base\Component;

class Elasticsearch extends Component
{
    /** @var Client */
    protected $connection;

    public $config;

    public function init()
    {
        $this->connection = \Elasticsearch\ClientBuilder::fromConfig($this->config);
    }

    public function client()
    {
        return $this->connection;
    }
}

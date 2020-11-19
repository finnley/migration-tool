<?php

namespace app\components\sharding;

trait Migration
{
    /**
     * @param $tableName
     * @return mixed
     */
    protected function getRawTableName($tableName)
    {
        return $this->db->schema->getRawTableName($tableName);
    }

    /**
     * @param null $startMid
     * @return int
     * @throws \Exception
     */
    protected function getStartShardingMid($startMid = null)
    {
        $startMid = isset($startMid) ? $startMid : 0;
        if ((!is_int($startMid)) || ($startMid < 0)) {
            throw new \Exception('invalid sharding start mid');
        }
        return $startMid;
    }

    /**
     * @param null $midNum
     * @return int
     * @throws \Exception
     */
    protected function getShardingMidNum($midNum = null)
    {
        $midNum = isset($midNum) ? $midNum : self::SHARDING_MID_NUM;
        if ((!is_int($midNum)) || ($midNum < 1)) {
            throw new \Exception('invalid sharding mid num');
        }
        return $midNum;
    }

    /**
     * @param $tableName
     * @param $callback
     * @param null $startMid
     * @param null $midNum
     * @throws \Exception
     */
    protected function midSharding($tableName, $callback, $startMid = null, $midNum = null)
    {
        $tableName = $this->getRawTableName($tableName);
        $startMid = $this->getStartShardingMid($startMid);
        $midNum = $this->getShardingMidNum($midNum);
        $endMid = $startMid + $midNum;
        for ($i = $startMid; $i < $endMid; ++$i) {
            call_user_func_array(
                $callback,
                ['tableName' => $tableName . self::SHARDING_TABLE_NAME_SEPARATOR . (string)$i]
            );
        }
    }

    protected function createMidShardingIndex(
        $indexName,
        $tableName,
        $columns,
        $unique = false,
        $startMid = null,
        $midNum = null)
    {
        $this->midSharding($tableName, function ($tableName) use ($columns, $unique, $indexName) {
            $this->createIndex(
                $tableName . '_' . $indexName,
                $tableName,
                $columns,
                $unique
            );
        }, $startMid, $midNum);
    }

    protected function dropMidShardingIndex(
        $indexName,
        $tableName,
        $startMid = null,
        $midNum = null)
    {
        $this->midSharding($tableName, function ($tableName) use ($indexName) {
            $this->dropIndex(
                $tableName . '_' . $indexName,
                $tableName
            );
        }, $startMid, $midNum);
    }

    /**
     * @param $tableName
     * @param $callback
     * @param null $startMid
     * @param null $midNum
     * @param null $startMonth
     * @param null $monthNum
     * @throws \Exception
     */
    protected function midMonthSharding(
        $tableName,
        $callback,
        $startMid = null,
        $midNum = null,
        $startMonth = null,
        $monthNum = null
    )
    {
        $tableName = $this->getRawTableName($tableName);
        $startMid = $this->getStartShardingMid($startMid);
        $midNum = $this->getShardingMidNum($midNum);
        $endMid = $startMid + $midNum;
        $month = isset($startMonth) ? $startMonth : self::SHARDING_START_MONTH;
        if ((!is_string($month)) || (date('Y-m', strtotime($month)) != $month)) {
            throw new \Exception('invalid sharding start month');
        }
        $monthNum = isset($monthNum) ? $monthNum : self::SHARDING_MONTH_NUM;
        if ((!is_int($monthNum)) || ($monthNum < 1)) {
            throw new \Exception('invalid sharding month num');
        }
        for ($i = 0; $i < $monthNum; ++$i) {
            $formattedMonth = date('Y_m', strtotime($month));
            for ($j = $startMid; $j < $endMid; ++$j) {
                call_user_func_array(
                    $callback,
                    [
                        'tableName' => $tableName . self::SHARDING_TABLE_NAME_SEPARATOR . (string)$j .
                            self::SHARDING_TABLE_NAME_SEPARATOR . $formattedMonth
                    ]
                );
            }
            $month = date('Y-m', strtotime($month . ' +1 month'));
        }
    }

    protected function createMidMonthShardingIndex(
        $indexName,
        $tableName,
        $columns,
        $unique = false,
        $startMid = null,
        $midNum = null,
        $startMonth = null,
        $monthNum = null)
    {
        $this->midMonthSharding($tableName, function ($tableName) use ($columns, $unique, $indexName) {
            $this->createIndex(
                $tableName . '_' . $indexName,
                $tableName,
                $columns,
                $unique
            );
        }, $startMid, $midNum, $startMonth, $monthNum);
    }

    protected function dropMidMonthShardingIndex(
        $indexName,
        $tableName,
        $startMid = null,
        $midNum = null,
        $startMonth = null,
        $monthNum = null)
    {
        $this->midMonthSharding($tableName, function ($tableName) use ($indexName) {
            $this->dropIndex(
                $tableName . '_' . $indexName,
                $tableName
            );
        }, $startMid, $midNum, $startMonth, $monthNum);
    }
}

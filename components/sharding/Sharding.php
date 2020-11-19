<?php

namespace app\components\sharding;

interface Sharding
{
    const SHARDING_MID_NUM = 1024;
    const SHARDING_START_MONTH = '2019-09';
    const SHARDING_MONTH_NUM = 12;
    const SHARDING_TABLE_NAME_SEPARATOR = '_';
}

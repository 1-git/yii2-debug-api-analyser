<?php

namespace oneGit\yii2DebugApiAnalyser\base;

/**
 * Class UrlBaseObject
 * @package oneGit\yii2DebugApiAnalyser\base
 */
class UrlBaseObject
{
    /**
     * Success status
     * 1-success/0-error
     */
    const SUCCESS_STATUS = 1;
    /**
     * Number of all sql queries for 1 api request
     * Good value: 30
     */
    const MAX_TOTAL_QUERIES_COUNT = 30;
    /**
     * Duration of all sql queries in milliseconds
     * Good value: 150
     */
    const MAX_TOTAL_QUERIES_TIME = 150;
    /**
     * Total number of duplicates
     * Good value: 1 (max 6)
     */
    const MAX_TOTAL_DUPLICATES = 6;
    /**
     * Max duration for 1 sql query
     * Good value: 30
     */
    const MAX_QUERY_TIME = 30;
    /**
     * Number of duplicates for one sql query
     * Good value: 1 (max 6)
     */
    const MAX_QUERY_DUPLICATE = 6;

    /**
     * @return array
     */
    public function getList(): array
    {
        return [];
    }
}

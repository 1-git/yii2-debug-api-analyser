<?php

namespace oneGit\yii2DebugApiAnalyser\base;

use yii\helpers\Url;
use PHPUnit\Framework\TestCase;
use oneGit\yii2DebugApiAnalyser\base\UrlHandlerInterface;

/**
 * Class ApiTest
 * @package oneGit\yii2DebugApiAnalyser\base
 */
class ApiTest extends TestCase
{
    /**
     * @var UrlHandlerInterface
     */
    protected static $urlHandler;

    /**
     * @var ErrorMessageHandler
     */
    protected static $errorHandler;

    /**
     * Call before test start
     */
    public static function setUpBeforeClass()
    {
        if (!self::$urlHandler) {
            self::$urlHandler = new UrlBaseHandler();
        }
        if (!self::$errorHandler) {
            self::$errorHandler = new ErrorMessageHandler(self::$urlHandler);
        }
    }

    /**
     * Returns url with settings from UrlBaseObject
     *
     * @return array
     */
    public function urlDataProvider(): array
    {
        $list = (new UrlBaseObject)->getList();
        $data = [];
        foreach ($list as $path) {
            $data[] = [UrlBaseObject::class, $path];
        }
        return $data;
    }

    /**
     * @dataProvider urlDataProvider
     *
     * @param $handler
     * @param $path
     */
    public function testPushAndPop($handler, $path)
    {
        //initTestedPage
        $fullUrl = self::$urlHandler->getApiUrl($path);
        $this->getContent($fullUrl);
        //getStatData
        $path = urlencode($path);//escape & //TODO move?
        $statsUrl = self::$urlHandler->getTestStatUrl($path);
        $json = $this->getContent($statsUrl);
        $data = json_decode($json, true);
        //analyzeStat
        $this->analyzeStat($handler, $data, $path);
    }

    /**
     * @param string $url
     * @param array $config
     * @return bool|string
     */
    protected function getContent(string $url, $config = []): string
    {
        return file_get_contents($url, false, stream_context_create($config));
    }

    /**
     * @param UrlBaseObject $handler
     * @param array $data
     * @param string $path
     */
    protected function analyzeStat($handler, array $data, string $path)
    {
        $tag = $data['data']['tag'];
        $message = self::$errorHandler->getMessage($path, $tag);
        $this->assertSame($handler::SUCCESS_STATUS, $data['status'], $message);
        if ($data['status'] === $handler::SUCCESS_STATUS) {
            $this->assertLessThanOrEqual($handler::MAX_TOTAL_QUERIES_COUNT, $data['data']['queryCount'], $message);
            $this->assertLessThanOrEqual($handler::MAX_TOTAL_QUERIES_TIME, $data['data']['queryTime'], $message);
            $this->assertLessThanOrEqual($handler::MAX_TOTAL_DUPLICATES, $data['data']['sumDuplicates'], $message);
            foreach ($data['data']['timings'] as $timings) {
                $this->assertLessThanOrEqual($handler::MAX_QUERY_TIME, $timings['duration'] * 1000, $message);
            }
            foreach ($data['data']['models'] as $models) {
                $this->assertLessThanOrEqual($handler::MAX_QUERY_DUPLICATE, $models['duplicate'], $message);
            }
        }
    }
}

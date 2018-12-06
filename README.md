Yii2 debug api analyser
---

You can analyze you queries in yii2 by using debug module. But if you have a lot of urls for testing,
this library will help you to do it automatically.

For testing your pages do the next things:

1 Activate yii2 debug module [see here](https://www.yiiframework.com/extension/yiisoft/yii2-debug).
To check it for working go to the next url

```
[YOUR_SITE]/debug
```

2 Add next parameters to configuration

```php
'controllerMap' => [
    'apis' => 'oneGit\yii2DebugApiAnalyser\base\controllers\ApisController',
],
```

Example of backend/config/main-local.php

```php
return [
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['*'],
            'controllerMap' => [
                'apis' => 'oneGit\yii2DebugApiAnalyser\base\controllers\ApisController',
            ],
        ],
    ],
],
```

2 Install phpunit and create your test folder with working simple tests. If you don't have it- find in the internet

3 Add next 3 files:

3.1 Create **UrlHandler.php** with your namespace and urls in function (see **[[YOUR DOMAIN]]** bellow)

```php
<?php

namespace tests\phpunit\apitest;

use oneGit\yii2DebugApiAnalyser\base\UrlBaseHandler;

/**
 * Class UrlHandler
 * @package tests\phpunit\apitest
 */
class UrlHandler extends UrlBaseHandler
{
    /**
     * @inheritdoc
     */
    public function getApiUrl(string $path): string
    {
        return "http://api.[[YOUR DOMAIN]]/{$path}";
    }

    /**
     * @inheritdoc
     */
    public function getTestStatUrl(string $path): string
    {
        return "http://admin.[[YOUR DOMAIN]]/debug/apis/test?path={$path}";
    }

    /**
     * @inheritdoc
     */
    public function getDebugUrl(string $tag): string
    {
        return "http://admin.[[YOUR DOMAIN]]/debug/default/view?tag={$tag}&panel=db";
    }
}

```

3.2 Create test file **ApiTest.php** with your namespace

```php
<?php

namespace tests\phpunit\apitest;

use oneGit\yii2DebugApiAnalyser\base\ApiTest as BaseApiTest;

/**
 * Class ApiTest
 * @package tests\phpunit\apitest
 */
class ApiTest extends BaseApiTest
{
    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        self::$urlHandler = new UrlHandler();
        parent::setUpBeforeClass();
    }
    
    /**
     * @inheritdoc
     */
    public function urlDataProvider(): array
    {
        return (new ListHandler)->getList();
    }
}
```

3.3 Create the file **ListHandler.php** (or other name) with your namespace and your paths (see **[[YOUR PAGES]]** bellow)

```php
<?php

namespace tests\phpunit\apictest;

use oneGit\yii2DebugApiAnalyser\base\UrlBaseObject;

/**
 * Class ListHandler
 * @package tests\phpunit\apictest
 */
class ListHandler extends UrlBaseObject
{
    /**
     * @inheritdoc
     */
    public function getList(): array
    {
        return [
            //[[YOUR PAGES]],
            'default/index',
            'office',
            'default/list?type=1',
        ];
    }
}
```

Alalyze constants in the class **UrlBaseObject* that you extend. You can configure the tests by changing the constants 
in the child class ListHandler. **UrlBaseObject** has such Constants:

```
SUCCESS_STATUS = 1;
MAX_TOTAL_QUERIES_COUNT = 30;
MAX_TOTAL_QUERIES_TIME = 150;
MAX_TOTAL_DUPLICATES = 6;
MAX_QUERY_TIME = 30;
MAX_QUERY_DUPLICATE = 6;
```

4 Run phpuit test using command phpunit

5 Analyze the result (here is the main indicators)

https://phpunit.readthedocs.io/ru/latest/textui.html

6 If you have more than 50 urls for testing add next row to debug settings

```php
  'historySize' => [[number, no les than number of urls]] //(default value- 50)
```

Example of settings

```php
return [
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module**UrlBaseObject*',
            'controllerMap' => [
                'apis' => 'oneGit\yii2DebugApiAnalyser\base\controllers\ApisController',
            ],
            'historySize' => 100,
        ],
    ],
],
```

It is important in case, when you want to open debug page with query info using the link in error 
message of phpunit. Debug module by default save only 50 latest pages. If your test have 100 pages and error at
25th page happens- you won't see info page in debug module. In than case you can see only pages from 51th 
till 100th. So modify  the parameter **historySize** for saving more number of pages


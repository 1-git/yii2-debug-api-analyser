<?php

namespace oneGit\yii2DebugApiAnalyser\base;

/**
 * Class ErrorMessageHandler
 * @package oneGit\yii2DebugApiAnalyser\base
 */
class ErrorMessageHandler
{
    /**
     * @var UrlBaseHandler
     */
    protected $pathHandler;

    /**
     * @var string
     */
    public $messageTemplate = "PAGE: {{apiUrl}}\nDEBUG: {{debugUrl}}\nTEST STAT: {{testStatUrl}}";

    /**
     * Current message
     * @var string
     */
    protected $message;

    /**
     * ErrorMessageHandler constructor.
     * @param UrlBaseHandler $pathHandler
     */
    public function __construct(UrlBaseHandler $pathHandler)
    {
        $this->pathHandler = $pathHandler;
    }

    /**
     * @param string $path
     * @param string $tag
     * @return string
     */
    public function getMessage(string $path, string $tag): string
    {
        return $this->activateTemplate()
            ->setApiUrl($path)
            ->setDebugUrl($tag)
            ->setTestStatUrl($path)
            ->getResult();
    }

    /**
     * @return $this
     */
    protected function activateTemplate()
    {
        $this->message = $this->messageTemplate;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    protected function setApiUrl(string $path)
    {
        $url = $this->pathHandler->getApiUrl($path);
        $this->message = str_replace('{{apiUrl}}', $url, $this->message);
        return $this;
    }


    /**
     * @param string $path
     * @return $this
     */
    protected function setDebugUrl(string $path)
    {
        $url = $this->pathHandler->getDebugUrl($path);
        $this->message = str_replace('{{debugUrl}}', $url, $this->message);
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    protected function setTestStatUrl(string $path)
    {
        $url = $this->pathHandler->getTestStatUrl($path);
        $this->message = str_replace('{{testStatUrl}}', $url, $this->message);
        return $this;
    }

    /**
     * @return string
     */
    protected function getResult()
    {
        return $this->message;
    }
}

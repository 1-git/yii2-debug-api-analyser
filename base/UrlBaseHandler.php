<?php

namespace oneGit\yii2DebugApiAnalyser\base;

/**
 * Class UrlBaseHandler
 * @package oneGit\yii2DebugApiAnalyser\base
 */
class UrlBaseHandler
{
    /**
     * Subdomain where api returns the results be opened
     *
     * @param string $path
     * @return string
     */
    public function getApiUrl(string $path): string
    {
        return "http://api.[[YOUR DOMAIN]]/{$path}";
    }

    /**
     * Subdomain where debugger could be opened
     *
     * @param string $path
     * @return string
     */
    public function getTestStatUrl(string $path): string
    {
        return "http://admin.[[YOUR DOMAIN]]/debug/apis/test?path={$path}";
    }

    /**
     * Subdomain where debugger could be opened
     *
     * @param string $tag
     * @return string
     */
    public function getDebugUrl(string $tag): string
    {
        return "http://admin.[[YOUR DOMAIN]]/debug/default/view?tag={$tag}&panel=db";
    }
}

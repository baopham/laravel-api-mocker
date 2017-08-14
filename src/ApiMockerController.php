<?php

namespace BaoPham\ApiMocker;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

/**
 * Class ApiMockerController
 * @package BaoPham\ApiMocker
 */
class ApiMockerController extends Controller
{
    /**
     * Defined in config/apimocker.php
     *
     * @var array
     */
    private $endpoints;

    /**
     * @var array
     */
    private $config;

    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->endpoints = config('apimocker.endpoints');

        $this->request = $request;
    }

    public function mock()
    {
        $this->config = $this->getConfigForEndpoint();

        if ($this->config) {
            return $this->getNewResponse();
        }

        throw new EndpointNotConfiguredException(
            sprintf(
                '%s %s is not configured in config/apimocker.php',
                $this->request->getMethod(),
                $this->request->getPathInfo()
            )
        );
    }

    protected function getConfigForEndpoint()
    {
        /** @var \Illuminate\Routing\Route $route */
        $route = Route::getCurrentRoute();

        foreach ($this->endpoints as $path => $config) {
            if ($path == $route->uri() && $this->checkRequestMethod()) {
                return $config;
            }
        }

        return null;
    }

    protected function checkRequestMethod()
    {
        if (! isset($this->config['methods'])) {
            return true;
        }

        return in_array($this->request->getMethod(), $this->config['methods']);
    }

    protected function getResponseContentFromFixture()
    {
        $fixturePath = $this->config['fixture'];

        $content = file_get_contents($fixturePath);

        $placeholders = [];

        if (! empty($this->config['placeholder'])) {
            $params = $this->request->all();

            foreach ($params as $name => $value) {
                if (starts_with($name, 'placeholder_')) {
                    $placeholder = ltrim($name, 'placeholder_');

                    $placeholders['{{' . $placeholder . '}}'] = $value;
                }
            }

            $content = strtr($content, $placeholders);
        }

        return $content;
    }

    protected function getNewResponse()
    {
        $content = $this->getResponseContentFromFixture($this->config['fixture']);

        $status = array_get($this->config, 'status', 200);

        return new Response($content, $status, ['Content-Type' => 'application/json']);
    }

}

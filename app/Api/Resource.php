<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\Response as ApiResponse;
use Generator;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Container\Container;
use Illuminate\Support\Str;
use InvalidArgumentException;

abstract class Resource
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @param array $parameters
     * @return void
     */
    public function __construct(array $parameters = [])
    {
        if (!isset($this->method)) {
            throw new InvalidArgumentException('Method is not set.');
        }

        if (!isset($this->endpoint)) {
            throw new InvalidArgumentException('Endpoint is not set.');
        }

        $this->setupEndpoint($parameters);
    }

    /**
     * @param array $parameters
     * @return void
     */
    protected function setupEndpoint(array $parameters)
    {
        if (empty($parameters)) {
            return;
        }

        $parameters = array_map(function (string $parameter) {
            return (Str::contains($parameter, '/')) ? urlencode($parameter) : $parameter;
        }, $parameters);

        $this->endpoint = Str::replaceArray('?', $parameters, $this->endpoint);
    }

    /**
     * @param array $params
     * @return \App\Api\Response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(array $params = []): ApiResponse
    {
        $response = $this->getClient()->request(
            $this->method,
            $this->getUri(),
            $this->getOptions($params)
        );

        return new ApiResponse($response);
    }

    /**
     * @param array $params
     * @return \Generator
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getGenerator(array $params = []): Generator
    {
        $client = $this->getClient();
        $options = $this->getOptions($params);
        $options['query']['page'] = 1;

        while ($options['query']['page']) {
            $response = new ApiResponse($client->request($this->method, $this->getUri(), $options));
            $options['query']['page'] = $response->getNextPage();

            yield $response;
        }
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getClient(): GuzzleClient
    {
        return Container::getInstance()->make(GuzzleClient::class);
    }

    /**
     * @return string
     */
    protected function getUri(): string
    {
        return 'api/v4/' . $this->endpoint;
    }

    /**
     * @param array $params
     * @return array
     */
    protected function getOptions(array $params): array
    {
        if (empty($params)) {
            return [];
        }

        if ($this->method === 'GET') {
            return ['query' => $params];
        }

        if ($this->method === 'POST') {
            return ['form_params' => $params];
        }

        throw new InvalidArgumentException('Invalid method: ' . $this->method);
    }
}

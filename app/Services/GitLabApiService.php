<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;

class GitLabApiService
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @param \GuzzleHttp\Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string      $search
     * @param string|null $group
     * @return array
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function searchProjects(string $search, string $group = null): array
    {
        $endpoint = $group ? sprintf('groups/%s/search', urlencode($group)) : 'search';

        return $this->request('GET', $endpoint, ['query' => ['scope' => 'projects', 'search' => $search]]);
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $options
     * @return mixed
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request(string $method, string $endpoint, array $options = [])
    {
        $response = $this->client->request($method, $this->getApiEndpoint($endpoint), $options);

        return json_decode((string) $response->getBody());
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function getApiEndpoint(string $endpoint)
    {
        return 'api/v4/' . $endpoint;
    }
}
<?php

namespace App\Commands;

use App\Api\Client;
use App\Factories\SearchResourceFactory;
use Carbon\Carbon;
use LaravelZero\Framework\Commands\Command;

class SearchProjectsCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'search:projects
                            {search : The search query}
                            {--group= : The group to search in}';

    /**
     * @var string
     */
    protected $description = 'Search projects';

    /**
     * @param \App\Api\Client                      $client
     * @param \App\Factories\SearchResourceFactory $factory
     * @return void
     */
    public function handle(Client $client, SearchResourceFactory $factory)
    {
        $resource = $factory->create($this->options())
            ->query([
                'scope' => 'projects',
                'search' => $this->argument('search'),
            ]);
        $responses = $client->request($resource);

        if (!$responses) {
            return $this->warn('No results');
        }

        $this->render($responses);
    }

    /**
     * @param array $items
     * @return void
     */
    protected function render(array $items)
    {
        foreach ($items as $item) {
            $updatedAt = Carbon::createFromTimeString($item->last_activity_at)->toDateTimeString();

            $this->table(
                ['Project', $item->path_with_namespace],
                [
                    $item->description ? ['Description', $item->description] : [],
                    ['Url', $item->web_url],
                    ['Updated At', $updatedAt],
                ],
                'box'
            );
        }
    }
}

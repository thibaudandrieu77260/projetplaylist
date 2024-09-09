<?php
// src/Service/WikipediaService.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WikipediaService
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getBiography(string $artistName): ?string
    {
        $query = str_replace(' ', '_', $artistName);
        $url = "https://en.wikipedia.org/api/rest_v1/page/summary/$query";

        try {
            $response = $this->client->request('GET', $url);
            $data = $response->toArray();

            if (isset($data['extract'])) {
                return $data['extract'];
            }
        } catch (\Exception $e) {
            // Handle exceptions or logging here if necessary
        }

        return null;
    }
}

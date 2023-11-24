<?php

namespace App\Actions;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class LinkSafeAction
{
    private Client $client;
    private string $google_safe_browsing_api_url;
    private string $google_safe_browsing_api_key;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->google_safe_browsing_api_key = env('GOOGLE_API_CHECKER_KEY');
        $this->google_safe_browsing_api_url = env('GOOGLE_API_CHECKER_URL');
    }

    /**
     * Check url for bad way
     *
     * @param string $original
     *
     * @return bool
     */
    public function __invoke(string $original): bool
    {
        if (!env('GOOGLE_API_CHECKER_ENABLE')) {
            return true;
        }

        $params = [
            'client' => [
                'clientId' => 'shortcut-1337',
                'clientVersion' => '1.5.2'
            ],
            'threatInfo' => [
                'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING'],
                'platformTypes' => ['WINDOWS', 'OSX', 'LINUX'],
                'threatEntryTypes' => ['URL'],
                'threatEntries' => [
                    ['url' => $original]
                ]
            ]
        ];

        $status = 0;
        try {
            $res = $this->client->request('POST', $this->google_safe_browsing_api_url, [
                'json' => $params,
                'query' => [
                    'key' => $this->google_safe_browsing_api_key
                ]
            ]);
            $status = $res->getStatusCode();

        } catch (GuzzleException $exception) {
            return false;
        }
        if ($status != 200) {
            return false;
        }
        return true;
    }
}

<?php

namespace Test\Functional;

use GuzzleHttp\Client;

/**
 * Use 'guzzlehttp/guzzle' lib for send special request type
 */
class MailerClient
{
    private Client $client;

    public function __construct()
    {
        /**
         * Base URI is used with relative requests
         * https://docs.guzzlephp.org/en/stable/quickstart.html?highlight=base_uri
         */
        $this->client = new Client([
            'base_uri' => 'http://mailer:8025',
        ]);
    }

    /**
     * Clear messages list in mailbox
     */
    public function clear(): void
    {
        // MailHog api url
        $this->client->delete('/api/v1/messages');
    }

    /**
     * Count mail in mailbox
     */
    public function hasEmailSentTo(string $to): bool
    {
        // MailHog api url
        $response = $this->client->get('/api/v2/search?kind=to&query=' . urlencode($to));
        /** @psalm-var array{total:int} $data */
        $data = json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        return $data['total'] > 0;
    }
}

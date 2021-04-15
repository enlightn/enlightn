<?php

namespace Enlightn\Enlightn\Reporting;

class API
{
    /**
     * @var \Enlightn\Enlightn\Reporting\Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $report
     * @return string|null
     */
    public function sendReport(array $report)
    {
        $response = $this->client->post('report', $report);

        if (isset($response['url'])) {
            return $response['url'];
        }

        return null;
    }
}

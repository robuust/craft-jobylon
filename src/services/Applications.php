<?php

namespace robuust\jobylon\services;

use Craft;
use craft\helpers\Json;
use GuzzleHttp\Client;
use robuust\jobylon\Plugin;
use yii\base\Component;

/**
 * Applications service.
 */
class Applications extends Component
{
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var Client
     */
    protected Client $client;

    /**
     * Initialize service.
     */
    public function init()
    {
        $this->settings = Plugin::getInstance()->getSettings();
        $this->client = Craft::createGuzzleClient([
            'base_uri' => $this->settings->host.'/'.$this->settings->apiVersion.'/',
            'headers' => [
                'X-App-Id' => $this->settings->appId,
                'X-App-Key' => $this->settings->appKey,
            ],
        ]);
    }

    /**
     * Create application.
     *
     * @param array $values
     *
     * @return array
     */
    public function createApplication(array $values): array
    {
        $request = $this->client->post('applications', [
            'multipart' => array_map(function ($key, $value) {
                return [
                    'name' => $key,
                    'contents' => $value,
                ];
            }, array_keys($values), array_values($values)),
        ]);

        return Json::decode((string) $request->getBody());
    }
}

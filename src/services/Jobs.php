<?php

namespace robuust\jobylon\services;

use Craft;
use craft\elements\Entry;
use craft\helpers\Json;
use DateTime;
use GuzzleHttp\Client;
use robuust\jobylon\Plugin;
use yii\base\Component;

/**
 * Jobs service.
 */
class Jobs extends Component
{
    /**
     * @var string
     */
    public const URL = 'https://feed.jobylon.com/feeds/';

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var Sections
     */
    protected $sections;

    /**
     * @var Section
     */
    protected $section;

    /**
     * @var EntryType
     */
    protected $entryType;

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
            'base_uri' => static::URL,
        ]);
        $this->sections = Craft::$app->getSections();

        $this->section = $this->sections->getSectionByHandle($this->settings->sectionHandle);
        list($this->entryType) = $this->sections->getEntryTypesByHandle($this->settings->entryTypeHandle);
    }

    /**
     * Get jobylon jobs.
     *
     * @param string $hash
     *
     * @return array
     */
    public function getJobs(string $hash): array
    {
        // Get jobs
        $request = $this->client->get($hash, [
            'query' => [
                'format' => 'json',
            ],
        ]);

        return Json::decode((string) $request->getBody());
    }

    /**
     * Get entry by job id.
     *
     * @param string $jobId
     *
     * @return Entry|null
     */
    public function getEntry(string $jobId): ?Entry
    {
        $query = Entry::find()->section($this->section);
        $query[$this->settings->jobIdField] = $jobId;

        return $query->status(null)->one();
    }

    /**
     * Get all entries.
     *
     * @return array
     */
    public function getEntries(): array
    {
        $query = Entry::find()->section($this->section);
        $query->orderBy($this->settings->createdField.' asc');

        return $query->status(null)->all();
    }

    /**
     * Import job.
     *
     * @param array $job
     *
     * @return bool
     */
    public function importJob(array $job): bool
    {
        $entry = $this->getEntry($job['id']);

        if (!$entry) {
            $entry = new Entry();
            $entry->sectionId = $this->section->id;
            $entry->typeId = $this->entryType->id;
            $entry->enabled = true;
            $entry->expiryDate = $job['to_date'] ? new DateTime($job['to_date']) : null;
            $entry->{$this->settings->jobIdField} = $job['id'];
        }

        $entry->title = $job['title'];
        $entry->{$this->settings->benefitsField} = $job['benefits'];
        $entry->{$this->settings->contactField} = [$job['contact']];
        $entry->{$this->settings->departmentsField} = array_map(fn ($department) => ['name' => $department['department']['name']], $job['departments']);
        $entry->{$this->settings->descriptionField} = $job['descr'];
        $entry->{$this->settings->locationsField} = array_map(fn ($location) => ['city' => $location['location']['city'], 'area' => $location['location']['area_1']], $job['locations']);
        $entry->{$this->settings->createdField} = new DateTime($job['dt_created']);
        $entry->{$this->settings->modifiedField} = new DateTime($job['dt_modified']);
        $entry->{$this->settings->salaryField} = [['min' => $job['salary']['salary_min'], 'max' => $job['salary']['salary_max']]];
        $entry->{$this->settings->skillsField} = $job['skills'];

        $layers = [];
        $layerFields = array_filter($job, fn ($key) => strpos($key, 'layers_') === 0, ARRAY_FILTER_USE_KEY);
        foreach ($layerFields as $key => $layer) {
            foreach ($layer as $value) {
                $layers[] = ['layer' => str_replace('layers_', '', $key), 'text' => $value['layer']['text']];
            }
        }
        $entry->{$this->settings->layersField} = $layers;

        return Craft::$app->getElements()->saveElement($entry);
    }

    /**
     * Clean up job.
     *
     * @param Entry $entry
     * @param array $jobs
     */
    public function cleanupJob(Entry $entry, array $jobs): bool
    {
        foreach ($jobs as $job) {
            if ($job['id'] == $entry->{$this->settings->jobIdField}) {
                return false;
            }
        }

        return Craft::$app->getElements()->deleteElement($entry);
    }
}
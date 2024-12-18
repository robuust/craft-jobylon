<?php

namespace robuust\jobylon\console\controllers;

use craft\console\Controller;
use yii\console\ExitCode;

/**
 * Import controller.
 */
class ImportController extends Controller
{
    /**
     * Import action.
     *
     * @param string $hash
     * @param string $environment
     *
     * @return int
     */
    public function actionIndex(string $hash, string $environment = 'feed'): int
    {
        $jobs = $this->module->jobs->getJobs($hash, $environment);
        $this->stdout('jobs found: '.count($jobs)."\n");

        // Import jobs
        $count = 0;
        foreach ($jobs as $job) {
            if ($this->module->jobs->importJob($job)) {
                ++$count;
            }
        }
        $this->stdout("jobs imported: {$count}\n");

        return ExitCode::OK;
    }

    /**
     * Cleanup action.
     *
     * @param array $hashes
     *
     * @return int
     */
    public function actionCleanup(array $hashes): int
    {
        $jobs = [];
        foreach ($hashes as $hash) {
            $jobs = array_merge($jobs, $this->module->jobs->getJobs($hash));
        }
        $this->stdout('jobs found: '.count($hashes)."\n");

        $entries = $this->module->jobs->getEntries();
        $this->stdout('entries found: '.count($entries)."\n");

        // Cleanup jobs
        $count = 0;
        foreach ($entries as $entry) {
            if ($this->module->jobs->cleanupJob($entry, $jobs)) {
                ++$count;
            }
        }
        $this->stdout("entries cleaned up: {$count}\n");

        return ExitCode::OK;
    }
}

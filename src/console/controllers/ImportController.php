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
     *
     * @return int
     */
    public function actionIndex(string $hash): int
    {
        $jobs = $this->module->jobylon->getJobs($hash);
        $this->stdout('jobs found: '.count($jobs)."\n");

        // Import jobs
        $count = 0;
        foreach ($jobs as $job) {
            if ($this->module->jobylon->importJob($job)) {
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
            $jobs = array_merge($jobs, $this->module->jobylon->getJobs($hash));
        }
        $this->stdout('jobs found: '.count($hashes)."\n");

        $entries = $this->module->jobylon->getEntries();
        $this->stdout('entries found: '.count($entries)."\n");

        // Cleanup jobs
        $count = 0;
        foreach ($entries as $entry) {
            if ($this->module->jobylon->cleanupJob($entry, $jobs)) {
                ++$count;
            }
        }
        $this->stdout("entries cleaned up: {$count}\n");

        return ExitCode::OK;
    }
}

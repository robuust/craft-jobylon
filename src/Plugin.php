<?php

namespace robuust\jobylon;

use robuust\jobylon\models\Settings;
use robuust\jobylon\services\Applications;
use robuust\jobylon\services\Jobs;

/**
 * Jobylon plugin.
 */
class Plugin extends \craft\base\Plugin
{
    /**
     * Initialize plugin.
     */
    public function init()
    {
        parent::init();

        // Register services
        $this->setComponents([
            'applications' => Applications::class,
            'jobs' => Jobs::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }
}

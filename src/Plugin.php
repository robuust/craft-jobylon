<?php

namespace robuust\jobylon;

use robuust\jobylon\models\Settings;
use robuust\jobylon\services\Jobylon;

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
            'jobylon' => Jobylon::class,
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

Jobylon plugin for Craft
=================

Plugin that allows you to import Jobylon entries.

## Requirements

This plugin requires Craft CMS 4.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require robuust/craft-jobylon

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Jobylon.

## Config

Create a file called `jobylon.php` in you Craft config folder with the following contents:

```php
<?php

return [
    // General
    'host' => 'https://staging.jobylon.com', // OR production url
    'apiVersion' => 'p1',
    'appId' => 'YOUR_APP_ID',
    'appKey' => 'YOUR_APP_KEY',
    // Section
    'sectionHandle' => 'YOUR_JOB_SECTION_HANDLE',
    'entryTypeHandle' => 'YOUR_JOB_ENTRY_TYPE_HANDLE',
    // Fields
    'jobIdField' => 'YOUR_JOB_ID_FIELD', // Number
    'benefitsField' => 'YOUR_JOB_BENEFITS_FIELD', // Table (1 PlainText text column)
    'imageField' => 'YOUR_JOB_IMAGE_FIELD', // Assets
    'contactField' => 'YOUR_JOB_CONTACT_FIELD', // Table (1 PlainText name column, 1 Email email column, 1 PlainText phone column)
    'photoField' => 'YOUR_JOB_PHOTO_FIELD', // Assets
    'departmentsField' => 'YOUR_JOB_DEPARTMENTS_FIELD', // Table (1 PlainText name column)
    'descriptionField' => 'YOUR_JOB_DESCRIPTION_FIELD', // Rich Text
    'locationsField' => 'YOUR_JOB_LOCATIONS_FIELD', // Table (1 PlainText city column, 1 PlainText area column)
    'createdField' => 'YOUR_JOB_CREATED_FIELD', // DateTime
    'modifiedField' => 'YOUR_JOB_MODIFIED_FIELD', // DateTime
    'salaryField' => 'YOUR_JOB_SALARY_FIELD', // Table (1 Number min column, 1 Number max column)
    'skillsField' => 'YOUR_JOB_SKILLS_FIELD', // Rich Text
    'layersField' => 'YOUR_JOB_LAYERS_FIELD', // Table (1 PlainText layer column, 1 PlainText text column)
];

```

## CLI Usage

Run `craft jobylon/import HASH` on the CLI to import the newest items.

Run `craft jobylon/import/cleanup HASH` on the CLI to clean up old items.

## Sample Application form

```twig
<form method="post" enctype="multipart/form-data">
    {{ csrfInput() }}
    {{ actionInput('jobylon/push/application') }}
    {{ hiddenInput('job_id', entry.jobId) }}
    {{ hiddenInput('source_type', 'applied') }}
    <input type="hidden" name="source_json" value='{"partner_name": "best-source"}' />

    {% if error is defined %}
        <p>{{ error }}</p>
    {% endif %}

    <label for="first_name">Voornaam</label>
    <input id="first_name" type="text" name="first_name" required />

    <label for="last_name">Achternaam</label>
    <input id="last_name" type="text" name="last_name" required />

    <label for="email">E-mailadres</label>
    <input id="email" type="email" name="email" required />

    <label for="phone">Telefoonnummer</label>
    <input id="phone" type="tel" name="phone" required />

    <label for="cv">CV</label>
    <input id="cv" type="file" name="cv" />

    <label for="motivation">Motivatie</label>
    <textarea id="motivation" name="message" required></textarea>

    <button type="submit">Versturen</button>
</form>
```

<?php

namespace robuust\jobylon\models;

use craft\base\Model;

/**
 * Settings model.
 */
class Settings extends Model
{
    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $apiVersion = 'p1';

    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $appKey;

    /**
     * @var string
     */
    public $sectionHandle;

    /**
     * @var string
     */
    public $entryTypeHandle;

    /**
     * @var string
     */
    public $jobIdField;

    /**
     * @var array
     */
    public $benefitsField;

    /**
     * @var array
     */
    public $contactField;

    /**
     * @var array
     */
    public $departmentsField;

    /**
     * @var string
     */
    public $descriptionField;

    /**
     * @var array
     */
    public $locationsField;

    /**
     * @var DateTime
     */
    public $createdField;

    /**
     * @var DateTime
     */
    public $modifiedField;

    /**
     * @var array
     */
    public $salaryField;

    /**
     * @var string
     */
    public $skillsField;

    /**
     * @var array
     */
    public $layersField;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['host', 'appId', 'appKey', 'sectionHandle', 'entryTypeHandle', 'jobIdField', 'benefitsField', 'contactField', 'departmentsField', 'descriptionField', 'locationsField', 'createdField', 'modifiedField', 'salaryField', 'skillsField', 'layersField'], 'required'],
        ];
    }
}

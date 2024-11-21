<?php

namespace robuust\jobylon\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
use craft\web\UploadedFile;
use Exception;

/**
 * Push controller.
 */
class PushController extends Controller
{
    /**
     * {@inheritdoc}
     */
    protected array|bool|int $allowAnonymous = true;

    /**
     * Push application to Jobylon.
     *
     * @return Response|null
     */
    public function actionApplication(): ?Response
    {
        $this->requirePostRequest();

        $values = $this->request->getBodyParams();

        // Remove unnecessary fields for proxying
        $csrf = Craft::$app->getConfig()->getGeneral()->csrfTokenName;
        unset($values['action'], $values[$csrf]);

        // Add files to values
        $files = ['cv', 'cover_letter', 'other_1', 'other_2', 'other_3', 'other_4', 'other_5'];
        foreach ($files as $file) {
            $values[$file] = UploadedFile::getInstanceByName($file);
        }

        $error = null;
        try {
            $result = $this->module->applications->createApplication($values);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            if ($this->request->getAcceptsJson()) {
                return $this->asJson([
                    'error' => $error,
                ]);
            }

            // Send the entry back to the template
            Craft::$app->getUrlManager()->setRouteParams([
                'error' => $error,
            ]);

            return null;
        }

        if ($this->request->getAcceptsJson()) {
            return $this->asJson($result);
        }

        return $this->redirectToPostedUrl((object) $result);
    }
}

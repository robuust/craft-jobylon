<?php

namespace robuust\jobylon\controllers;

use Craft;
use craft\web\Controller;
use craft\web\Response;
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

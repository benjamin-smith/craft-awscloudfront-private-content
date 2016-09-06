<?php
namespace Craft;

use AwsCloudfront\Utilities\KeyFile;

class AwsCloudfrontController extends BaseController
{

    protected $keyFile = null;

    public function init()
    {
        craft()->userSession->requireAdmin();

        $this->keyFile = new KeyFile();
    }

    public function actionListPolicies(array $variables = array())
    {
        $variables['policies'] = craft()->awsCloudfront_policy->getAllPolicies();

        $variables['keyFileExists'] = $this->keyFile->exists();
        $variables['keyFilePath'] = $this->keyFile->getExpectedKeyFilePath();

        $this->renderTemplate('awscloudfront/listPolicies', $variables);
    }

    public function actionEditPolicy(array $variables = array())
    {
        if (empty($variables['policy'])) {
            if (!empty($variables['policyId'])) {
                $variables['policy'] = AwsCloudfront_PolicyRecord::model()->findById($variables['policyId']);
                if (!$variables['policy']) {
                    throw new HttpException(404);
                }
            } else {
                $variables['policy'] = new AwsCloudfront_PolicyModel();
            }
        }

        $this->renderTemplate('awscloudfront/editPolicy', $variables);
    }

    public function actionSavePolicy()
    {
        $this->requirePostRequest();

        $policy = new AwsCloudfront_PolicyModel();
        $policy->id           = craft()->request->getPost('id');
        $policy->name         = craft()->request->getPost('name');
        $policy->handle       = craft()->request->getPost('handle');
        $policy->expires      = craft()->request->getPost('expires');
        $policy->restrictByIp = craft()->request->getPost('restrictByIp');

        if ($policy->validate()) {
            if (craft()->awsCloudfront_policy->savePolicy($policy)) {
                craft()->userSession->setNotice(Craft::t('Policy saved.'));
                $this->redirectToPostedUrl($policy);
            } else {
                craft()->userSession->setError(Craft::t('Couldn’t save policy.'));
            }
        }

        // Send the policy back to the template
        craft()->urlManager->setRouteVariables(array(
            'policy' => $policy
        ));
    }

    public function actionDeletePolicy()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();

        $policyId = craft()->request->getRequiredPost('id');

        craft()->awsCloudfront_policy->deletePolicy($policyId);

        $this->returnJson(array('success' => true));
    }
}

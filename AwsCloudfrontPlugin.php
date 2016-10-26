<?php
namespace Craft;

class AwsCloudfrontPlugin extends BasePlugin
{

    public function init()
    {
        require_once __DIR__ . '/vendor/autoload.php';
    }

    public function getName()
    {
         return Craft::t('AWS CloudFront Private Contents');
    }

    public function getDescription()
    {
        return Craft::t('Create CloudFront signed URLs on the fly to protect your assets.');
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/benjamin-smith/craft-awscloudfront-private-content/blob/master/README.md';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/benjamin-smith/craft-awscloudfront-private-content/master/releases.json';
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Benjamin Smith';
    }

    public function getDeveloperUrl()
    {
        return 'https://www.benjaminsmith.com';
    }

    public function hasCpSection()
    {
        return true;
    }

    public function registerCpRoutes()
    {
        return array(
            'awscloudfront/policies'                              => ['action' => 'awsCloudfront/listPolicies'],
            'awscloudfront/policy/new'                            => ['action' => 'awsCloudfront/editPolicy'],
            'awscloudfront/policy/(?P<policyId>\d+)'              => ['action' => 'awsCloudfront/editPolicy'],
            'awscloudfront/policy/save'                           => ['action' => 'awsCloudfront/savePolicy'],
            'awscloudfront/policy/deletePolicy/(?P<policyId>\d+)' => ['action' => 'awsCloudfront/deletePolicy'],
       );
    }

    public function prepSettings($settings)
    {
        return $settings;
    }

    public function onAfterInstall()
    {
        $storagePath = craft()->path->getStoragePath();
        $pluginStoragePath = $storagePath . 'awscloudfront/';

        IOHelper::ensureFolderExists($pluginStoragePath);
    }

}

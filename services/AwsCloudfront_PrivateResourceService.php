<?php
namespace Craft;

use AwsCloudfront\Utilities\Client;
use AwsCloudfront\Utilities\Policy;

class AwsCloudfront_PrivateResourceService extends BaseApplicationComponent
{

    protected $hostUrl;

    protected $client;

    protected $policyUtil;

    public function __construct()
    {
        $this->hostUrl  = craft()->config->get('hostUrl', 'awscloudfront');
        $awsRegion      = craft()->config->get('awsRegion', 'awscloudfront');
        $privateKeyPath = craft()->config->get('privateKeyPath', 'awscloudfront');
        $keyPairId      = craft()->config->get('keyPairId', 'awscloudfront');

        $this->client = new Client($awsRegion, $this->hostUrl, $privateKeyPath, $keyPairId);
        $this->policyUtil = new Policy();
    }

    public function getPrivateUrl($resourceKey = null, $policyHandle = null)
    {
        $policyModel = craft()->awsCloudfront_policy->getPolicyByHandle($policyHandle);

        if ($policyModel===null) {
            throw new Exception("AWS Cloudfront policy \"{$policyHandle}\" not found");
        }

        if ($this->policyUtil->isCustomPolicy($policyModel)) {
            $resourceUrl = $this->hostUrl . '/' . $resourceKey;
            $ipAddress = craft()->request->getIpAddress();
            $policyDoc = $this->policyUtil->generatePolicy($policyModel, $resourceUrl, $ipAddress);
        } else {
            $policyDoc = $policyModel->expires;
        }

        return $this->client->getPrivateUrl($resourceKey, $policyDoc);
    }

}

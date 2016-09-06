<?php
namespace Craft;

class AwsCloudfrontVariable {

    public function getPrivateUrl($url = null, $policyHandle = null)
    {
        return craft()->awsCloudfront_privateResource->getPrivateUrl($url, $policyHandle);
    }

}

<?php
namespace AwsCloudfront\Utilities;

use Craft\AwsCloudfront_PolicyModel;
use Craft\DateTimeHelper;

class Policy {

    public function isCustomPolicy(AwsCloudfront_PolicyModel $policyModel)
    {
        return $policyModel->restrictByIp !== null;
    }

    public function generatePolicy(AwsCloudfront_PolicyModel $policyModel, $resourceKey, $ipAddress)
    {
        $policyDoc = $this->getBaseCustomPolicyDocument();

        $policyDoc['Statement'][0]['Resource'] = $resourceKey;

        if ($policyModel->restrictByIp && $ipAddress) {
            $policyDoc['Statement'][0]['Condition']['IpAddress'] = [
                'AWS:SourceIp' => $ipAddress . '/32',
            ];
        }

        if ($policyModel->expires) {
            $policyDoc['Statement'][0]['Condition']['DateLessThan'] = [
                'AWS:EpochTime' => $this->generateExpiresTimestamp($policyModel->expires),
            ];
        }

        return $policyDoc;
    }

    protected function getBaseCustomPolicyDocument()
    {
        return [
            'Statement' => [[
                'Resource' => null,
                'Condition' => [],
            ]]
        ];
    }

    protected function generateExpiresTimestamp($expires)
    {
        return DateTimeHelper::currentTimeStamp() + $expires;
    }

}

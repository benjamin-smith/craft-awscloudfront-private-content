<?php
namespace AwsCloudfront\Utilities;

use Aws\CloudFront\CloudFrontClient;

class Client {

    public function __construct($awsRegion, $hostUrl, $privateKeyPath, $keyPairId)
    {
        $this->hostUrl = $hostUrl;

        $this->privateKeyPath = $privateKeyPath;

        $this->keyPairId = $keyPairId;

        $this->awsClient = new CloudFrontClient([
            'region'  => $awsRegion,
            'version' => '2014-11-06'
        ]);
    }

    public function getPrivateUrl($resourceKey, $policyData)
    {
        $config = [
            'url'         => $this->hostUrl . '/' . $resourceKey,
            'private_key' => $this->privateKeyPath,
            'key_pair_id' => $this->keyPairId
        ];

        if (is_integer($policyData)) {
            $config['expires'] = $policyData;
        } else {
            $config['policy'] = json_encode($policyData, JSON_UNESCAPED_SLASHES);
        }

        echo $config['policy'];

        return $this->awsClient->getSignedUrl($config);

    }

}

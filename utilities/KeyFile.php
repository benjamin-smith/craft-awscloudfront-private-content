<?php
namespace AwsCloudfront\Utilities;

use Craft\IOHelper;
use Craft\PathService;

class KeyFile {

    protected $pathService;

    protected $keyFileName = 'cloudfront.pem';

    public function __construct()
    {
        $this->pathService = new PathService;
    }

    public function exists()
    {
        $keyFilePath = $this->getExpectedKeyFilePath();
        return IOHelper::fileExists($keyFilePath);
    }

    public function getExpectedKeyFilePath()
    {
        $craftStoragePath = $this->pathService->getStoragePath();
        return $craftStoragePath . 'awscloudfront/' . $this->keyFileName;
    }

}

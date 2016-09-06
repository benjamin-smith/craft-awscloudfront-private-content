<?php
namespace Craft;

class AwsCloudfront_PolicyRecord extends BaseRecord
{

    public function getTableName()
    {
        return 'awscloudfront_policies';
    }

    public function defineIndexes()
    {
        return [
            ['columns' => ['name'], 'unique' => true],
            ['columns' => ['handle'], 'unique' => true],
        ];
    }

    public function scopes()
    {
        return [
            'ordered' => ['order' => 'name'],
        ];
    }

    protected function defineAttributes()
    {
        return [
            'name'         => [AttributeType::String, 'required' => true],
            'handle'       => [AttributeType::Handle, 'required' => true],
            'expires'      => [AttributeType::Number, 'required' => true],
            'restrictByIp' => AttributeType::Bool,
        ];
    }
}

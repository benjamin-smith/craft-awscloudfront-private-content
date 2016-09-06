<?php
namespace Craft;

class AwsCloudfront_PolicyModel extends BaseModel
{

    public function __toString()
    {
        return $this->handle;
    }

    protected function defineAttributes()
    {
        return [
            'id'           => ['type' => AttributeType::Number, 'required' => true],
            'name'         => ['type' => AttributeType::String, 'required' => true],
            'handle'       => ['type' => AttributeType::Handle, 'required' => true],
            'expires'      => ['type' => AttributeType::Number, 'required' => true, 'min' => 1],
            'restrictByIp' => ['type' => AttributeType::Bool],
        ];
    }

}

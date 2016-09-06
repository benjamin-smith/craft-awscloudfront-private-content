<?php
namespace Craft;

class AwsCloudfront_PolicyService extends BaseApplicationComponent
{

    protected $_haveFetchedAll = false;

    protected $_modelsByHandle = [];

    public function getAllPolicies($indexBy = null)
    {
        if (!$_haveFetchedAll) {

            $results = craft()->db->createCommand()
                ->from('awscloudfront_policies')
                ->queryAll();

            foreach ($results as $result) {
                $model = new AwsCloudfront_PolicyModel($result);
                $this->_modelsByHandle[$model->handle] = $model;
            }
        }

        if ($indexBy == 'handle') {
            $models = $this->_modelsByHandle;
        } else if (!$indexBy) {
            $models = array_values($this->_modelsByHandle);
        } else {
            $models = [];
            foreach ($this->_modelsByHandle as $model) {
                $models[$model->$indexBy] = $model;
            }
        }

        return $models;
    }

    public function getPolicyByHandle($handle)
    {
        if (
            !$this->_haveFetchedAll &&
            !array_key_exists($handle, $this->_modelsByHandle)
        ) {
            $result = $this->_createPolicyQuery()
                ->where('handle = :handle', array(':handle' => $handle))
                ->queryRow();

            if ($result) {
                $policy = new AwsCloudfront_PolicyModel($result);
            } else {
                $policy = null;
            }

            return $policy;

            $this->_modelsByHandle[$handle] = $policy;
        }

        if (isset($this->_modelsByHandle[$handle]))
        {
            return $this->_modelsByHandle[$handle];
        }
    }

    public function savePolicy(AwsCloudfront_PolicyModel $policy)
    {
        if ($policy->id) {
            $policyRecord = AwsCloudfront_PolicyRecord::model()->findById($policy->id);

            if (!$policyRecord) {
                throw new Exception(Craft::t('Can’t find the policy with ID “{id}”.', array('id' => $policy->id)));
            }
        } else {
            $policyRecord = new AwsCloudfront_PolicyRecord();
        }

        $policyRecord->name = $policy->name;
        $policyRecord->handle = $policy->handle;
        $policyRecord->expires = $policy->expires;
        $policyRecord->restrictByIp = $policy->restrictByIp;

        $recordValidates = $policyRecord->validate();

        if ($recordValidates) {
            $policyRecord->save(false);

            // Now that we have a policy ID, save it on the model
            if (!$policy->id) {
                $policy->id = $policyRecord->id;
            }

            return true;
        } else {
            $policy->addErrors($policyRecord->getErrors());
            return false;
        }
    }

    public function deletePolicy($policyId)
    {
        craft()->db->createCommand()->delete('awscloudfront_policies', array('id' => $policyId));
        return true;
    }

    protected function _createPolicyQuery()
    {
        return craft()->db->createCommand()
            ->select('id, name, handle, restrictByIp, expires')
            ->from('awscloudfront_policies')
            ->order('name');
    }

}

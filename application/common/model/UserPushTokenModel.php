<?php
namespace app\common\model;

use app\common\library\Os;
use think\Model;

class UserPushTokenModel extends Model
{
    protected $name = CMS_PREFIX . 'user_push_token';
    protected  $pk = array('user_id', 'access_id', 'device_id');

    const STATUS_LOGIN = 1;
    const STATUS_LOGOUT = 2;


    public function createUserPushToken($userId, $accessId, $deviceId, $os, $pushToken)
    {
        $userPushToken = $this->findByUserId($userId, $accessId, $deviceId);
        if ($userPushToken) {
            $data['user_id'] = $userId;
            $data['access_id'] = $accessId;
            $data['device_id'] = $deviceId;
            $data['status'] = UserPushTokenModel::STATUS_LOGIN;
            $data['push_token'] = $pushToken;
            $data['update_time'] = date('Y-m-d H:i:s');

            $this->isUpdate(true)->save($data);
        } else {
            $data['user_id'] = $userId;
            $data['access_id'] = $accessId;
            $data['device_id'] = $deviceId;
            $data['status'] = UserPushTokenModel::STATUS_LOGIN;
            $data['os'] = $os;
            $data['push_token'] = $pushToken;
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['update_time'] = $data['create_time'];

            $result = $this->save($data);
            if (!$result) {
                return false;
            }
        }

        //联合主键，find设置方法；顺序与pk字段一致
        $pk = ['user_id'=>$userId, 'access_id' => $accessId, 'device_id' => $deviceId];
        $userPushToken = UserPushTokenModel::get($pk);

        return $userPushToken;
    }

    public function findByUserId($userId, $accessId, $deviceId)
    {
        $where['user_id'] = $userId;
        $where['access_id'] = $accessId;
        $where['device_id'] = $deviceId;

        $resultSet = $this->where($where)->limit(1)->select();
        if (count($resultSet) < 1) {
            return false;
        }

        return $resultSet[0];
    }

    public function logout($userId, $accessId, $deviceId)
    {
        $data['user_id'] = $userId;
        $data['access_id'] = $accessId;
        $data['device_id'] = $deviceId;
        $data['status'] = UserPushTokenModel::STATUS_LOGOUT;

        $count = $this->isUpdate(true)->save($data);
        if ($count < 1) {
            return false;
        }

        return true;
    }

    public function getAndroidPushTokens($userId, $accessId = '')
    {
        if (empty($accessId)) {
            $accessId = config('middleware_access_id');
        }
        $where = [
            'user_id' => $userId,
            'access_id' => $accessId,
            'status' => UserPushTokenModel::STATUS_LOGIN,
            'os' => Os::Android
        ];

        $UserPushTokenModel = new UserPushTokenModel();
        $resultSet = $UserPushTokenModel->where($where)->order('update_time desc')->select();
        if (count($resultSet) == 0) {
            return false;
        }

        return $resultSet;
    }

    public function getIosPushTokens($userId, $accessId = '')
    {
        if (empty($accessId)) {
            $accessId = config('middleware_access_id');
        }
        $where = [
            'user_id' => $userId,
            'access_id' => $accessId,
            'status' => UserPushTokenModel::STATUS_LOGIN,
            'os' => Os::iOS
        ];

        $UserPushTokenModel = new UserPushTokenModel();
        $resultSet = $UserPushTokenModel->where($where)->order('update_time desc')->select();
        if (count($resultSet) == 0) {
            return false;
        }

        return $resultSet;
    }
}

?>
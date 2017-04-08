<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\RequestCache;

/**
 * Class User
 * @package App\Model
 *
 * @property int $id    一般用户操作没有url_slug，直接用id操作，但最终往页面输出数据的时候可剔除
 * @property string $email    邮件系统没做前，email先暂时充当登陆用户名 todo
 * @property string $password
 * @property string $name    昵称，随意修改
 *
 * @property int $status    0：默认值； 1：一般用户； 2往后预留； 4：ban用户； 100：管理员；  最终往页面输出数据的时候可剔除
 * @property int $role    0：默认值； 1：一般用户； 2：参与者； 3：发起者
 *
 * @property string $avatar
 * @property int $follow_count
 *
 * @property string $create_time
 * @property string $update_time
 * @property string $register_time
 * @property string $active_time
 *
 */


class User extends Model
{
    protected $table = 'user';
    protected $hidden = ['password'];

    public $timestamps = false;

    const CACHE_KEY = 'cache';

    /**
     * 登录用，通过获取账号查找相关数据
     * 暂时的，会改动
     * todo
     *
     * @param $account
     * @return mixed
     */
    public static function findByAccount($account) {
        return User::where("email", '=', $account)->first();
    }

    /**
     * 验证密码是否匹配
     *
     * @param User $model
     * @param $password
     * @return bool
     */
    public static function checkPassword(User $model, $password){
        if($model->password == UserOperate::encryptPassword($password, $model->create_time)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 用户信息对外输出时进行重写
     * 可输出信息包括：id, name, status, role, avatar, follow_count
     * 考虑到许多后续处理需要用到id , status, 这里的格式化依旧会输出id， status, 但最终像页面输出时要剔除掉
     *
     * @param $old_info
     * @return array
     */
    public static function UserInfoOutput($old_info){
        $safe_info = array('id','name','status','role','avatar','follow_count');
        $new_info = [];
        foreach($old_info as $k => $v){
            if(in_array($k, $safe_info)){
                $new_info[$k] = $v;
            }
        }
        return $new_info;
    }

    /**
     * 获取单用户信息，已对输出数据格式化
     *
     * @param $user_id
     * @return mixed
     */
    public static function getUser($user_id){
        $user = User::where('id',$user_id)->first();
        if($user){
            $user = $user->toArray();
            $user = User::UserInfoOutput($user);
        }
        return $user;
    }

    /**
     * 获取多用户信息，已单独对输出数据格式化
     *
     * @param $user_ids
     * @param array $select
     * @return array
     */
    public static function getUsers($user_ids, $select = array()){
        if(!$select){
            $select = ['id','name','status','role','avatar','follow_count'];
        }
        $data = self::getUsersInfo($user_ids, $select);
        return $data;
    }
    /**
     * @param array $user_ids
     * @param array $select
     * @return array
     */
    public static function getUsersInfo($user_ids = array(), $select = array()){
        $data = array();
        if(!$user_ids){
            return $data;
        }
        $user_ids_str = implode(',', $user_ids);
        if(!$user_ids_str){
            return $data;
        }
        $users = User::whereIn('id',$user_ids);
        if(!$select){
            $select = ['id','name','status','role','avatar','follow_count'];
        };
        $users = $users->select($select);
        $users = $users->get()->toArray();

        foreach($users as $user){
            if(isset($user['id'])){
                $data[$user['id']] = $user;
            }else{
                $data[] = $user;
            }
        }
        return $data;
    }

    public static function getUserFollowCount($user_id){
        $follow_count = User::where('id',$user_id)->first()->follow_count;
        return $follow_count;
    }

    public static function updateUserFollowcount($user_id, $operate_num){
        $model = User::where('id',$user_id)->first();
        $model->follow_count = $model->follow_count + $operate_num;
        $model->save();

        $cache_key = self::CACHE_KEY . '_user_info_' . $user_id;
        $cache = RequestCache::setDirtValue($cache_key);
    }
}

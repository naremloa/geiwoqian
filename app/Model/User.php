<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * @package App\Model
 *
 * @property int $id
 * @property string $password
 * @property string $url_slug
 * @property string $name
 * @property string $email
 *
 * @property int $status    0：默认值； 1：一般用户； 2往后预留； 4：ban用户； 100：管理员；
 * @property int $role    0：默认值； 1：一般用户； 2：参与者； 3：发起者
 * @property int $follow_count
 * @property string $avatar
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

    /**
     * 登录用，通过获取账号查找相关数据
     * 暂时的，会改动
     *
     * @param $account
     * @return mixed
     */
    public static function findByAccount($account) {
        return User::where("name", '=', $account)->first();
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
     * 可输出信息包括：id, name, url_slug, avatar, cover, intro, status, role
     * 其中，后续处理要注意，若是用户角色不为发起者，cover, intro 两个输出信息没有意义，可剔除
     * 考虑到许多后续处理需要用到id , status, 这里的格式化依旧会输出id， status, 但最终像页面输出时要剔除掉
     * 用户信息考虑删除掉 cover, intro,
     *
     * @param $old_info
     * @return array
     */
    public static function UserInfoOutput($old_info){
        $safe_info = array('id','name','url_slug','avatar','cover','intro','status','role');
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
     * 其中，后续处理要注意，若是用户角色不为发起者，cover, intro 两个输出信息没有意义，可剔除
     *
     * @param $user_ids
     * @param array $select
     */
    public static function getUsers($user_ids, $select = array()){
        if(!$select){
            $select = ['id', 'name', 'url_slug', 'intro', 'avatar', 'cover','status','role'];
        }
        $data = self::getUsersInfo($user_ids, $select);
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
            $select = ['id', 'name', 'url_slug', 'intro', 'avatar', 'cover'];
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
}

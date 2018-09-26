<?php

namespace app\models;

use Yii;
use yii\db\Query;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public static function has_permission($permission, $url)
    {
        $user = \Yii::$app->user;
        if($user->isSuperAdmin)
        {
            return true;
        }
        $menu = (new \yii\db\Query())
            ->select(['ai.name', 'ai.description', 'ai.data', 'aic.can_add', 'aic.can_edit', 'aic.can_delete'])
            ->from('auth_assignment aa')
            ->innerJoin('auth_item_child aic', 'aa.item_name = aic.parent')
            ->innerJoin('auth_item ai', 'ai.name = aic.child')
            ->orderBy('ai.position ASC')
            ->where(['aa.user_id' => $user->id])
            ->andWhere('ai.name LIKE :url', [':url' => '%'.$url.'%'])
            ->one();

        switch($permission)
        {
            case "can_add":
                return $menu['can_add'];
                break;
            case "can_edit":
                return $menu['can_edit'];
                break;
            case "can_delete":
                return $menu['can_delete'];
                break;
        }

        return false;
    }
    public static function can_view($url)
    {
        $user = \Yii::$app->user;
        if($user->isSuperAdmin)
        {
            return true;
        }
        $menu = (new \yii\db\Query())
            ->select(['ai.name', 'ai.description', 'ai.data', 'aic.can_add', 'aic.can_edit', 'aic.can_delete'])
            ->from('auth_assignment aa')
            ->innerJoin('auth_item_child aic', 'aa.item_name = aic.parent')
            ->innerJoin('auth_item ai', 'ai.name = aic.child')
            ->orderBy('ai.position ASC')
            ->where(['aa.user_id' => $user->id]);
        if($url == '/')
        {
            $menu->andWhere('ai.name LIKE :url', [':url' => $url]);
        }
        else{
            $menu->andWhere('ai.name LIKE :url', [':url' => '%'.$url.'%']);
        }

        $menu = $menu->one();

        if($menu){
            return true;
        }

        return false;
    }

    public static function is_admin($id)
    {
        if(Yii::$app->user->isSuperadmin)
        {
            return true;
        }
        if((new \yii\db\Query())->select('COUNT(*)')->from('auth_assignment')->where("user_id = :user_id AND item_name = 'Admin'", [':user_id' => $id])->scalar() > 0){
            return true;
        }
        return false;
    }

    public static function getRole($id) {
        $query = (new \yii\db\Query())
            ->select('item_name')
            ->from('auth_assignment')
            ->where("user_id = :user_id", [':user_id' => $id])
            ->one();

        return $query['item_name'];
    }

    public static function is_in_role($id, $role)
    {
        if(Yii::$app->user->isSuperadmin)
        {
            return true;
        }
        if((new \yii\db\Query())->select('COUNT(*)')->from('auth_assignment')->where("user_id = :user_id AND item_name = :role", [':user_id' => $id, ':role' => $role])->scalar() > 0){
            return true;
        }
        return false;
    }

    public static function findUsersByRole($role)
    {
        return (new \yii\db\Query())
            ->select('*')
            ->from('auth_assignment')
            ->leftJoin('user', 'user.id = auth_assignment.user_id')
            ->where("item_name = '{$role}'")
            ->all();
    }

    public static function getSubCustomers($return_ids = false) {
        $user = \webvimark\modules\UserManagement\models\User::getCurrentUser();
        if(Yii::$app->user->isSuperadmin  || $user::hasRole(['Admin'], $superAdminAllowed = true)) {
            $customers = Customer::find()->all();

        } else if ($user::hasRole(['manager'])) {
            $my_users = (new Query())
                ->select('id')
                ->from('user')
                ->where('id=' . Yii::$app->user->id)
                ->orWhere('parent_id=' . Yii::$app->user->id)
                ->all();

            $my_users_ids = [];
            $my_users_ids[0] = Yii::$app->user->id;
            foreach ($my_users as $my_user) {
                $my_users_ids[] = $my_user['id'];
            }
            $my_users_ids = implode(',', $my_users_ids);

            $customers = Customer::find()
                ->where('user_id IN (' . $my_users_ids . ')')
                ->all();

        } else {
            $customers = Customer::find()
                ->where('user_id=' . Yii::$app->user->id)
                ->all();
        }

        if(!$return_ids) {
            return $customers;

        } else {
            $customers_ids = [];
            $customers_ids[0] = '-1';

            foreach ($customers as $customer) {
                $customers_ids[] = $customer->id;
            }

            return $customers_ids;
        }
    }
}

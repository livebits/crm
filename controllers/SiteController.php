<?php

namespace app\controllers;

use app\models\Customer;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\AuthItem;
use app\models\AuthItemChild;
use webvimark\modules\UserManagement\models\rbacDB\Permission;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $customers = Customer::find()->all();
        $clues_count = $customers_count = $deals_count = 0;

        foreach ($customers as $customer) {
            if($customer->status == Customer::$CLUE) {
                $clues_count++;
            } else if($customer->status == Customer::$CUSTOMER) {
                $customers_count++;
            } else if($customer->status == Customer::$DEALING) {
                $deals_count++;
            }
        }

        return $this->render('dashboard', compact('clues_count', 'customers_count', 'deals_count'));
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionMenus()
    {
        $selected_data = [
            'name' => "",
            'description' => "",
            'data' => "",
            'position' => "",
        ];

        if (Yii::$app->request->get('action') == 'create') {

        } else if (Yii::$app->request->get('action') == 'update') {
            if (!\app\models\User::has_permission('can_edit', '/index/menus')) {
                throw new ForbiddenHttpException(Yii::t('yii', 'شما اجازه دسترسی به این بخش را ندارید'));
            }
            $selected_data = AuthItem::find()->where('name = :name', [':name' => Yii::$app->request->get('name')])->one();
        } else if (Yii::$app->request->get('action') == 'delete') {
            if (!\app\models\User::has_permission('can_delete', '/index/menus')) {
                throw new ForbiddenHttpException(Yii::t('yii', 'شما اجازه دسترسی به این بخش را ندارید'));
            }
            $selected_data = AuthItem::find()->where('name = :name', [':name' => Yii::$app->request->get('name')])->one();
            $selected_data->description = "";
            $selected_data->data = "";
            $selected_data->save();
            return $this->redirect(['/site/menus']);
        }

        $data = Yii::$app->request->post();
        if ($data) {
            if (@$data['name']) {
                $auth_item = AuthItem::find()->where('name = :name', [':name' => $data['name']])->one();
                if (!$auth_item) {
                    $auth_item = new AuthItem();
                    $auth_item->name = $data['name'];
                    $auth_item->type = 3;
                }
                if ($auth_item->description == '') {
                    if (!\app\models\User::has_permission('can_add', '/index/menus')) {
                        throw new ForbiddenHttpException(Yii::t('yii', 'شما اجازه دسترسی به این بخش را ندارید'));
                    }
                }

                $auth_item->data = $data['data'];
                $auth_item->position = $data['position'];
                $auth_item->description = $data['description'];
                $auth_item->save();
            }
        }

        $query = AuthItem::find()->where('type = 3');

        $menu_url = clone $query;
        $menu_url->andWhere('description != ""');

        $menus = $menu_url->orderBy('position ASC')->all();
        $all_urls = $query->select('name')->asArray()->all();

        $urls = [];
        foreach ($all_urls as $item) {
            $urls[] = $item['name'];
        }

        return $this->render('menus', [
            'urls' => $urls,
            'menus' => $menus,
            'selected_data' => $selected_data,
        ]);
    }

    public static function getUserMenu()
    {
        $user = Yii::$app->user;
        $urls = (new \yii\db\Query())
            ->select(['ai.name', 'ai.description', 'ai.data', 'ai.position'])
            ->from('auth_assignment aa')
            ->innerJoin('auth_item_child aic', 'aa.item_name = aic.parent')
            ->innerJoin('auth_item ai', 'ai.name = aic.child')
            ->orderBy('ai.position ASC')
            ->where(['aa.user_id' => $user->id, 'type' => 3])
            ->distinct()
            ->all();

        return $urls;
    }

    public static function getAdminMenu()
    {
        $user = Yii::$app->user;
        $urls = (new \yii\db\Query())
            ->select(['ai.name', 'ai.description', 'ai.data'])
            ->from('auth_item ai')
            ->orderBy('ai.position ASC')
            ->where(['ai.type' => 3])
            ->andWhere('description != ""')
            ->all();
//        print_r($urls);die;

        return $urls;
    }

    public function actionRoles()
    {
        $selected_data = [
            'name' => "",
            'description' => "",
        ];
        $selected_menus = [];
        $selected_permissions = [];
        $query = AuthItem::find()->where('type = 3');

        if (Yii::$app->request->get('action') == 'create') {

        } else if (Yii::$app->request->get('action') == 'update') {
            if (!\app\models\User::has_permission('can_edit', '/site/roles')) {
                throw new ForbiddenHttpException(Yii::t('yii', 'شما اجازه دسترسی به این بخش را ندارید'));
            }
            $selected_data = AuthItem::find()->where('name = :name', [':name' => Yii::$app->request->get('name')])->one();
            $selected_menus = AuthItemChild::find()
                ->select(['ai.name AS name', 'ai.description AS description', 'auth_item_child.can_add AS can_add', 'auth_item_child.can_edit AS can_edit', 'auth_item_child.can_delete AS can_delete'])
                ->innerJoin('auth_item ai', 'ai.name = auth_item_child.child')
                ->where('parent= :name AND ai.type = 3', [':name' => Yii::$app->request->get('name')])
                ->all();

            $selected_permissions = [];
            foreach ($selected_menus as $item) {
                if ($item->can_add == 1) {
                    $selected_permissions[$item->name][] = 'can_add';
                }
                if ($item->can_edit == 1) {
                    $selected_permissions[$item->name][] = 'can_edit';
                }
                if ($item->can_delete == 1) {
                    $selected_permissions[$item->name][] = 'can_delete';
                }
            }
        } else if (Yii::$app->request->get('action') == 'delete') {
            if (!\app\models\User::has_permission('can_delete', '/site/roles')) {
                throw new ForbiddenHttpException(Yii::t('yii', 'شما اجازه دسترسی به این بخش را ندارید'));
            }
            $selected_data = AuthItem::find()->where('name = :name', [':name' => Yii::$app->request->get('name')])->one();
            $selected_data->description = "";
            $selected_data->save();
            return $this->redirect(['/site/roles']);
        } else {
            $query = AuthItem::find()->where('type = 1');
        }

        $data = Yii::$app->request->post();

        if ($data) {
            $new = false;
            if (@$data['original_name']) {
                $auth_item = AuthItem::find()->where('name = :original_name', [':original_name' => $data['original_name']])->one();
            }
            if (@$data['name']) {
                $auth_item = AuthItem::find()->where('name=:name', [':name' => $data['name']])->one();
                $new = true;
            }
            if (!$auth_item) {
                if (!\app\models\User::has_permission('can_add', '/site/menus')) {
                    throw new ForbiddenHttpException(Yii::t('yii', 'شما اجازه دسترسی به این بخش را ندارید'));
                }
                $new = true;
                $auth_item = new AuthItem();
            }
            $auth_item->name = $data['name'];
            $auth_item->type = 1;
            $auth_item->description = $data['description'];
            if ($auth_item->save()) {
                if ($new) {
                    $auth_child = new AuthItemChild();
                    $auth_child->parent = $auth_item['name'];
                    $auth_child->child = 'Admin';
                    $auth_child->save();
                }
            }

            return $this->redirect(['/site/roles']);
        }


        $menu_url = clone $query;
        $menu_url->andWhere('description != ""');

        $menus = $menu_url->all();
        $all_urls = $query->all();

        $urls = [];
        foreach ($all_urls as $item) {
            $urls[] = $item['name'];
        }

        return $this->render('roles', [
            'urls' => $all_urls,
            'menus' => $menus,
            'selected_data' => $selected_data,
            'selected_menus' => $selected_menus,
            'selected_permissions' => $selected_permissions,
        ]);
    }

    public function actionSetChildRoutes()
    {
        $posted_data = Yii::$app->request->post();
        $permissions = [];
        foreach ($posted_data as $key => $item) {
            if (strpos($key, '_0_')) {
                $name = substr($key, strpos($key, '_0_') + 3);
                $permissions[$name][] = substr($key, 0, strpos($key, '_0_'));
            }
        }

        $posted_routes = Yii::$app->request->post('child_routes');
        $all_routes = AuthItemChild::find()
            ->select(['ai.name AS name', 'ai.description AS description'])
            ->innerJoin('auth_item ai', 'ai.name = auth_item_child.child')
            ->where('parent= :name AND ai.type = 3', [':name' => Yii::$app->request->get('id')])
            ->asArray()->all();

        foreach ($all_routes as $key => $item) {
            $auth_child = AuthItemChild::find()
                ->where('parent = :parent AND child = :child', [':parent' => Yii::$app->request->get('id'), ':child' => $item['name']])
                ->one();

            if (!in_array($item['name'], $posted_routes)) {
                $auth_child->delete();
                unset($all_routes[$key]);
            } else {
                if (isset($permissions[$item['name']])) {
                    $auth_child->can_add = in_array('can_add', $permissions[$item['name']]) ? 1 : 0;
                    $auth_child->can_edit = in_array('can_edit', $permissions[$item['name']]) ? 1 : 0;
                    $auth_child->can_delete = in_array('can_delete', $permissions[$item['name']]) ? 1 : 0;
                    $auth_child->save();
                }
            }
        }

        $urls = [];
        foreach ($all_routes as $item) {
            $urls[] = $item['name'];
        }

        foreach ($posted_routes as $item) {
            if (!in_array($item, $urls)) {
                $auth_child = new AuthItemChild();
                $auth_child->parent = Yii::$app->request->get('id');
                $auth_child->child = $item;
                if (isset($permissions[$item])) {
                    if (isset($permissions[$item]['can_add'])) {
                        $auth_child->can_add = 1;
                    }
                    if (isset($permissions[$item]['can_edit'])) {
                        $auth_child->can_edit = 1;
                    }
                    if (isset($permissions[$item]['can_delete'])) {
                        $auth_child->can_delete = 1;
                    }
                }
                $auth_child->save();
            }
        }

        return $this->redirect(['/site/roles?action=update&name=' . Yii::$app->request->get('id')]);
    }

    public function actionUserPermissions($id)
    {
        $user = User::findOne($id);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        $permissionsByGroup = [];
        $permissions = Permission::find()
            ->andWhere([
                Yii::$app->getModule('user-management')->auth_item_table . '.name' => array_keys(Permission::getUserPermissions($user->id))
            ])
            ->joinWith('group')
            ->all();

        foreach ($permissions as $permission) {
            $permissionsByGroup[@$permission->group->name][] = $permission;
        }

        return $this->render('user_permissions', [
            'user' => $user,
            'permissionsByGroup' => $permissionsByGroup,
//            'urls' => $all_urls,
//            'menus' => $menus,
//            'selected_data' => $selected_data,
//            'selected_menus' => $selected_menus,
        ]);
    }

    public function actionSetRoles($id)
    {
        if (!Yii::$app->user->isSuperadmin AND Yii::$app->user->id == $id) {
            Yii::$app->session->setFlash('error', UserManagementModule::t('back', 'You can not change own permissions'));
            return $this->redirect(['set', 'id' => $id]);
        }

        $oldAssignments = array_keys(Role::getUserRoles($id));

        // To be sure that user didn't attempt to assign himself some unavailable roles
        $newAssignments = array_intersect(Role::getAvailableRoles(true, true), (array)Yii::$app->request->post('roles', []));

        $toAssign = array_diff($newAssignments, $oldAssignments);
        $toRevoke = array_diff($oldAssignments, $newAssignments);

        foreach ($toRevoke as $role) {
            User::revokeRole($id, $role);
        }

        foreach ($toAssign as $role) {
            User::assignRole($id, $role);
        }

        Yii::$app->session->setFlash('success', UserManagementModule::t('back', 'Saved'));

        return $this->redirect(['user-permissions', 'id' => $id]);
    }

    public function actionUsers()
    {
        $searchModel = new \app\models\User();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        return $this->render('users', compact('searchModel', 'dataProvider'));
    }
}

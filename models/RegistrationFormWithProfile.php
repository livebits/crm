<?php

namespace app\models;


use webvimark\modules\UserManagement\models\forms\RegistrationForm;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\ArrayHelper;
use Yii;

class RegistrationFormWithProfile extends RegistrationForm
{
    public $firstName;
    public $lastName;

    /**
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['firstName', 'lastName'], 'required'],
            [['firstName', 'lastName'], 'string'],
            [['firstName', 'lastName'], 'string', 'max' => 50],
            [['firstName', 'lastName'], 'trim'],
            [['firstName', 'lastName'], 'purgeXSS'],
        ]);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'firstName' => 'نام',
            'lastName' => 'نام خانوادگی',
        ]);
    }


    /**
     * Look in parent class for details
     *
     * @param User $user
     */
    protected function saveProfile($user)
    {
        $model = new UserProfile();

        $model->user_id = $user->id;

        $model->firstName = $this->firstName;
        $model->lastName = $this->lastName;

        $model->save(false);

        // add default role to registered user
        $roles = (array)Yii::$app->getModule('user-management')->rolesAfterRegistration;

        foreach ($roles as $role) {
            User::assignRole($user->id, $role);
        }
    }
}
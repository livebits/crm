<?php
/**
 * @var yii\web\View $this
 * @var array $permissionsByGroup
 * @var webvimark\modules\UserManagement\models\User $user
 */

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\models\rbacDB\Role;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

BootstrapPluginAsset::register($this);
$this->title = UserManagementModule::t('back', 'Roles and permissions for user:') . ' ' . $user->username;

$this->params['breadcrumbs'][] = ['label' => UserManagementModule::t('back', 'Users'), 'url' => ['/user-management/user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h2 class="lte-hide-title"><?= $this->title ?></h2>

<div class="row">
	<div class="col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>
					<span class="glyphicon glyphicon-th"></span> <?= UserManagementModule::t('back', 'Roles') ?>
				</strong>
			</div>
			<div class="panel-body">

				<?= Html::beginForm(['set-roles', 'id'=>$user->id]) ?>

				<?php foreach (Role::getAvailableRoles(true) as $aRole): ?>
					<label>
						<?php $isChecked = in_array($aRole['name'], ArrayHelper::map(Role::getUserRoles($user->id), 'name', 'name')) ? 'checked' : '' ?>

						<?php if ( Yii::$app->getModule('user-management')->userCanHaveMultipleRoles ): ?>
							<input type="checkbox" <?= $isChecked ?> name="roles[]" value="<?= $aRole['name'] ?>">

						<?php else: ?>
							<input type="radio" <?= $isChecked ?> name="roles" value="<?= $aRole['name'] ?>">

						<?php endif; ?>

						<?= $aRole['description'] ?>
					</label>

					<?= GhostHtml::a(
						'<span class="glyphicon glyphicon-edit"></span>',
						['/index/roles?action=update&name='.$aRole['name']],
						['target'=>'_blank']
					) ?>
					<br/>
				<?php endforeach ?>

				<br/>

				<?php if ( Yii::$app->user->isSuperadmin OR Yii::$app->user->id != $user->id ): ?>

					<?= Html::submitButton(
						'<span class="glyphicon glyphicon-ok"></span> ' . UserManagementModule::t('back', 'Save'),
						['class'=>'btn btn-primary btn-sm']
					) ?>
				<?php else: ?>
					<div class="alert alert-warning well-sm text-center">
						<?= UserManagementModule::t('back', 'You can not change own permissions') ?>
					</div>
				<?php endif; ?>


				<?= Html::endForm() ?>
			</div>
		</div>
	</div>
</div>
<?php
$this->registerJs(<<<JS

$('.role-help-btn').off('mouseover mouseleave')
	.on('mouseover', function(){
		var _t = $(this);
		_t.popover('show');
	}).on('mouseleave', function(){
		var _t = $(this);
		_t.popover('hide');
	});
JS
);
?>
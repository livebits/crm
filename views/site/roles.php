<?php
/**
 * @var $this \app\components\UiView
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \app\models\searchModels\UserDriverSearch
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\jui\AutoComplete;
use yii\web\JsExpression;

$this->title = 'مدیریت سطح دسترسی ها';
?>
    <div class="panel panel-dark panel-alt" style="margin-bottom: 0px;">
        <?php if (@$_GET['action'] == 'create' || @$_GET['action'] == 'update') { ?>

            <div class="page-title">
                <div class="title_left">

                </div>
                <div class="title_right" style="width: 100%;text-align: left;">

                    <a href="roles" class="btn btn-info panel-edit">
                        <span class="fa fa-arrow-left"></span>
                        لیست منوها
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <?php $title = $_GET['action'] == 'create' ? 'افزودن سطح دسترسی جدید' : 'ویرایش سطح دسترسی'; ?>
                            <h2><?= $title ?></h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/>

                            <form id="role-form" class="form-horizontal" action="<?= Yii::$app->homeUrl ?>site/roles"
                                  method="post" role="form">
                                <input type="hidden" name="original_name"
                                       value="<?= @$_GET['name'] != "" ? $_GET['name'] : "" ?>">
                                <input id="form-token" required type="hidden"
                                       name="<?= Yii::$app->request->csrfParam ?>"
                                       value="<?= Yii::$app->request->csrfToken ?>"/>
                                <div class="form-group field-permission-description required">
                                    <label class="control-label col-sm-3" for="description">نام سطح دسترسی</label>
                                    <div class="col-sm-6">
                                        <input type="text" id="permission-description" class="form-control" name="name"
                                               maxlength="255" autofocus="" value="<?= $selected_data['name'] ?>">
                                        <div class="help-block help-block-error "></div>
                                    </div>

                                </div>
                                <div class="form-group field-permission-name required">
                                    <label class="control-label col-sm-3" for="name">عنوان سطح دسترسی</label>
                                    <div class="col-sm-6">
                                        <?php echo AutoComplete::widget([
                                            'name' => 'description',
                                            'id' => 'name',
                                            'value' => $selected_data['description'],
                                            'clientOptions' => [
                                                'source' => $urls,
                                                'minLength' => '1',
                                                'autoFill' => true,
                                                'select' => new JsExpression("function( event, ui ) {
//											$('#name').val(ui.item.id);//#memberssearch-family_name_id is the id of hiddenInput.
										}")
                                            ],
                                            'options' => [
                                                'class' => 'form-control',
                                                'dir' => 'auto',
                                            ]
                                        ]);
                                        ?>
                                        <!--								<input type="text" required id="name" class="form-control" name="name" maxlength="64">-->
                                        <div class="help-block help-block-error "></div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-success"><span
                                                    class="glyphicon glyphicon-plus-sign"></span>ذخیره
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <?php if ($_GET['action'] == 'update') { ?>
                                <div class="panel-body">
                                    <h4 style="margin-bottom: 20px;">منوهای مربوط به این دسترسی را انتخاب کنید</h4>

                                    <?= Html::beginForm(['set-child-routes', 'id' => $selected_data['name']]) ?>

                                    <div class="row">

                                        <div class="col-sm-3 text-right">
									<span id="show-only-selected-routes" class="btn btn-default btn-sm">
										<i class="fa fa-minus"></i> نمایش انتخاب شده ها
									</span>
                                            <span id="show-all-routes" class="btn btn-default btn-sm hide">
										<i class="fa fa-plus"></i>نمایش همه
									</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <input id="search-in-routes" autofocus="on" type="text"
                                                   class="form-control input-sm" placeholder="Search route">
                                        </div>


                                        <div class="col-sm-3">
                                            <?= Html::submitButton(
                                                '<span class="glyphicon glyphicon-ok"></span> ' . 'ذخیره',
                                                ['class' => 'btn btn-primary btn-sm']
                                            ) ?>
                                        </div>
                                    </div>

                                    <hr/>

                                    <div id="routes-list">
                                        <? foreach ($menus as $menu) {
                                            $checked = '';
                                            foreach ($selected_menus as $item) {
                                                if ($item['name'] == $menu['name']) {
                                                    $checked = "checked";
                                                }
                                            }
                                            $can_add_str = '';
                                            $can_edit_str = '';
                                            $can_delete_str = '';
                                            if (isset($selected_permissions[$menu['name']])) {
                                                $can_add_str = in_array("can_add", $selected_permissions[$menu['name']]) ? "checked" : "";
                                                $can_edit_str = in_array("can_edit", $selected_permissions[$menu['name']]) ? "checked" : "";
                                                $can_delete_str = in_array("can_delete", $selected_permissions[$menu['name']]) ? "checked" : "";
                                            }
                                            ?>
                                            <label class="route-label col-md-12"
                                                   style="height: 30px;border-bottom: 1px solid #333333;">
                                                <div style="float: right;">

                                                    <input type="checkbox" class="route-checkbox" name="child_routes[]"
                                                           value="<?= $menu['name'] ?>" <?= $checked ?>>

                                                    <span class="route-text"><?= $menu['description'] ?>
                                                    </span>
                                                </div>
                                                <div style="float: left;">
                                                    <input type="checkbox" <?= $can_add_str ?>
                                                           name="can_add_0_<?= $menu['name'] ?>">افزودن
                                                    <input type="checkbox" <?= $can_edit_str ?>
                                                           name="can_edit_0_<?= $menu['name'] ?>">ویرایش
                                                    <input type="checkbox" <?= $can_delete_str ?>
                                                           name="can_delete_0_<?= $menu['name'] ?>">حذف

                                                </div>
                                            </label>
                                        <? } ?>
                                    </div>

                                    <hr/>
                                    <?= Html::submitButton(
                                        '<span class="glyphicon glyphicon-ok"></span>' . 'ذخیره',
                                        ['class' => 'btn btn-primary btn-sm']
                                    ) ?>

                                </div>
                            <? } ?>
                            <?= Html::endForm() ?>

                        </div>
                    </div>
                </div>
            </div>

        <?php } else { ?>

            <div class="page-title">
                <div class="title_left">

                </div>

                <div class="title_right" style="width: 100%;text-align: left;">

                    <a href="roles?action=create" class="btn btn-info panel-edit">
                        <span class="fa fa-plus"></span>
                        افزودن دسترسی جدید
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">

                            <h2>لیست سطح دسترسی ها</h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br/>

                            <table class="kv-grid-table table table-bordered table-striped kv-table-wrap">
                                <colgroup>
                                    <col width="50">
                                <thead>

                                <tr>
                                    <th style="width: 6%;"><span style="color:#000000;">ردیف</span></th>
                                    <th data-col-seq="2" style="width: 30%;"><span style="color: #0a0a0a">عنوان سطح دسترسی</span>
                                    </th>
                                    <th data-col-seq="3" style="width: 64%;"><span
                                                style="color: #0a0a0a">نام سطح دسترسی</span></th>
                                    <th class="action-column" style="width: 4%;"><span
                                                style="color: #0a0a0a">عملیات</span></th>
                                </tr>

                                </thead>
                                <tbody>
                                <?php
                                $i = 1;
                                foreach ($menus as $item) { ?>
                                    <tr data-key="<?= $item['name'] ?>">
                                        <td><?= $i ?></td>
                                        <td data-col-seq="2"><?= $item['description'] ?></td>
                                        <td data-col-seq="3" dir="auto"
                                            style="text-align: right"><?= $item['name'] ?></td>
                                        <td>
                                            <a href="<?= Yii::$app->homeUrl ?>site/roles?action=update&name=<?= $item['name'] ?>"
                                               title="Update" aria-label="Update" data-pjax="0">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                            <a href="<?= Yii::$app->homeUrl ?>site/roles?action=delete&name=<?= $item['name'] ?>"
                                               title="Delete" aria-label="Delete"
                                               data-confirm="Are you sure you want to delete this item?"
                                               data-method="post" data-pjax="0">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?
                                    $i++;
                                } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>


<?php
$js = <<<JS

var routeCheckboxes = $('.route-checkbox');
var routeText = $('.route-text');

// For checked routes
var backgroundColor = '#D6FFDE';

function showAllRoutesBack() {
	$('#routes-list').find('.hide').each(function(){
		$(this).removeClass('hide');
	});
}

//Make tree-like structure by padding controllers and actions
routeText.each(function(){
	var _t = $(this);

	var chunks = _t.html().split('/').reverse();
	var margin = chunks.length * 40 - 40;

	if ( chunks[0] == '*' )
	{
		margin -= 40;
	}

	_t.closest('label').css('margin-left', margin);

});

// Highlight selected checkboxes
routeCheckboxes.each(function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
});

// Change background on check/uncheck
routeCheckboxes.on('change', function(){
	var _t = $(this);

	if ( _t.is(':checked') )
	{
		_t.closest('label').css('background', backgroundColor);
	}
	else
	{
		_t.closest('label').css('background', 'none');
	}
});


// Hide on not selected routes
$('#show-only-selected-routes').on('click', function(){
	$(this).addClass('hide');
	$('#show-all-routes').removeClass('hide');

	routeCheckboxes.each(function(){
		var _t = $(this);

		if ( ! _t.is(':checked') )
		{
			_t.closest('label').addClass('hide');
			_t.closest('div.separator').addClass('hide');
		}
	});
});

// Show all routes back
$('#show-all-routes').on('click', function(){
	$(this).addClass('hide');
	$('#show-only-selected-routes').removeClass('hide');

	showAllRoutesBack();
});

// Search in routes and hide not matched
$('#search-in-routes').on('change keyup', function(){
	var input = $(this);

	if ( input.val() == '' )
	{
		showAllRoutesBack();
		return;
	}

	routeText.each(function(){
		var _t = $(this);

		if ( _t.html().indexOf(input.val()) > -1 )
		{
			_t.closest('label').removeClass('hide');
			_t.closest('div.separator').removeClass('hide');
		}
		else
		{
			_t.closest('label').addClass('hide');
			_t.closest('div.separator').addClass('hide');
		}
	});
});

JS;

$this->registerJs($js);
?>
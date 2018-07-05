<?php
/**
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

use yii\jui\AutoComplete;
use yii\web\JsExpression;

$this->title = 'مدیریت منوها';
?>
<div class="panel panel-dark panel-alt">
    <?php if (@$_GET['action'] == 'create' || @$_GET['action'] == 'update') { ?>

        <div class="page-title">
            <div class="title_left">

            </div>
            <div class="title_right" style="width: 100%;text-align: left;">

                <a href="menus" class="btn btn-info panel-edit">
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
                        <h2>ایجاد منو جدید</h2>
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

                        <form id="role-form" class="form-horizontal" action="<?= Yii::$app->homeUrl ?>site/menus"
                              method="post" role="form">
                            <input id="form-token" required type="hidden" name="<?= Yii::$app->request->csrfParam ?>"
                                   value="<?= Yii::$app->request->csrfToken ?>"/>
                            <div class="form-group field-permission-description required">
                                <label class="control-label col-sm-3" for="description">آیکن منو</label>
                                <div class="col-sm-6">
                                    <input type="text" id="permission-data" class="form-control" name="data"
                                           maxlength="255" autofocus="" value="<?= $selected_data['data'] ?>">
                                    <div class="help-block help-block-error "></div>
                                </div>
                            </div>
                            <div class="form-group field-permission-description required">
                                <label class="control-label col-sm-3" for="description">جایگاه منو</label>
                                <div class="col-sm-6">
                                    <input type="number" id="permission-position" class="form-control" name="position"
                                           maxlength="255" autofocus="" value="<?= $selected_data['position'] ?>">
                                    <div class="help-block help-block-error "></div>
                                </div>
                            </div>
                            <div class="form-group field-permission-description required">
                                <label class="control-label col-sm-3" for="description">عنوان منو</label>
                                <div class="col-sm-6">
                                    <input type="text" id="permission-description" class="form-control"
                                           name="description" maxlength="255" autofocus=""
                                           value="<?= $selected_data['description'] ?>">
                                    <div class="help-block help-block-error "></div>
                                </div>
                            </div>
                            <div class="form-group field-permission-name required">
                                <label class="control-label col-sm-3" for="name">آدرس URL</label>
                                <div class="col-sm-6">
                                    <?php echo AutoComplete::widget([
                                        'name' => 'name',
                                        'id' => 'name',
                                        'value' => $selected_data['name'],
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
                                                class="glyphicon glyphicon-plus-sign"></span> ذخیره
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>

        <div class="page-title">
            <div class="title_left">

            </div>
            <div class="title_right" style="width: 100%;text-align: left;">

                <a href="menus?action=create" class="btn btn-info panel-edit">
                    <span class="fa fa-plus"></span>
                    ایجاد منو جدید
                </a>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>لیست منوها</h2>
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
                                <th data-col-seq="3" style="width: 10%;"><span style="color: #0a0a0a">آیکن منو</span>
                                </th>
                                <th data-col-seq="3" style="width: 30%;"><span style="color: #0a0a0a">عنوان منو</span>
                                </th>
                                <th data-col-seq="3" style="width: 64%;"><span style="color: #0a0a0a">آدرس URL</span>
                                </th>
                                <th class="action-column" style="width: 4%;"><span style="color: #0a0a0a">عملیات</span>
                                </th>
                            </tr>

                            </thead>
                            <tbody>
                            <?php
                            $i = 1;
                            foreach ($menus as $item) { ?>
                                <tr data-key="<?= $item['name'] ?>">
                                    <td><?= $i ?></td>
                                    <td data-col-seq="2" style="text-align: center"><i
                                                class="fa <?= $item['data'] ?>"></i></td>
                                    <td data-col-seq="3"><?= $item['description'] ?></td>
                                    <td data-col-seq="4" dir="auto" style="text-align: right"><?= $item['name'] ?></td>
                                    <td>
                                        <a href="<?= Yii::$app->homeUrl ?>site/menus?action=update&name=<?= $item['name'] ?>"
                                           title="Update" aria-label="Update" data-pjax="0">
                                            <span class="glyphicon glyphicon-pencil"></span>
                                        </a>
                                        <a href="<?= Yii::$app->homeUrl ?>site/menus?action=delete&name=<?= $item['name'] ?>"
                                           title="Delete" aria-label="Delete"
                                           data-confirm="Are you sure you want to delete this item?" data-method="post"
                                           data-pjax="0">
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
</div>

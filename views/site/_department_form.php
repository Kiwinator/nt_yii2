<?
	use yii\helpers\Html;
	use yii\widgets\Pjax;
	use yii\widgets\ActiveForm;
	
	$this->registerJsFile('/web/js/department/form.js', ['depends' => [\yii\web\JqueryAsset::classname()]]);
	$this->registerJsFile('/web/js/form.js', ['depends' => [\yii\web\JqueryAsset::classname()]]);
?>
<div class="students-form">
    <? Pjax::begin(['id' => 'department_form_pjax', 'timeout' => 0, 'options' => ['class' => 'pjax-loader']])?>
        <? $form = ActiveForm::begin(['id' => (isset($model->id) ? 'form_update' : 'form_create')])?>
        <? if (isset($model->id)) : ?>
            <div class="" style="display:none">
                <?= $form->field($model, 'id', ['enableAjaxValidation' => true])->hiddenInput(['placeholder' => 'Код', 'class' => 'form-control capitalcase'])?>
            </div>
        <? endif; ?>
        <div class="row">
            <div class="col-sm-4 col-md-6">
                <?= $form->field($model, 'name', ['enableAjaxValidation' => true])->textInput(['placeholder' => 'Название', 'class' => 'form-control capitalcase'])?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::button('Сохранить', ['id' => (isset($model->id) ? 'btnSubmitUpdate' : 'btnSubmitCreate'), 'class' => 'btn btn-success']) ?>
        </div>
        <? ActiveForm::end()?>
    <? Pjax::end()?>
</div>
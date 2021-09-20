<?
	use yii\helpers\Html;
	use yii\widgets\Pjax;
	use yii\widgets\ActiveForm;
	
	$this->title = 'Создание сотрудника';
	$this->registerJsFile('/web/js/employee/form.js', ['depends' => [\yii\web\JqueryAsset::classname()]]);
	$this->registerJsFile('/web/js/form.js', ['depends' => [\yii\web\JqueryAsset::classname()]]);
?>
<div class="employee-form">
    <h2><?= Html::encode($this->title) ?></h2>
    <? Pjax::begin(['id' => 'employee_form_pjax', 'timeout' => 0, 'options' => ['class' => 'pjax-loader']])?>
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
        <div class="row">
            <div class="col-sm-4 col-md-6">
                <?= $form->field($model, 'department', ['enableAjaxValidation' => true])->checkboxList($departments,['separator' => '<br>', 
                	'item' => function($index, $label, $name, $checked, $value) use ($model) {
                		$checked = '';
                		if (!empty($model->department) && in_array($value, $model->department)) {
                			$checked = 'checked';
                		}
            			
            			return "<label class='checkbox col-md-4' style='font-weight: normal;'><input type='checkbox' {$checked} name='{$name}' value='{$value}'> {$label}</label>";
        			}
        		])?>
            </div>
        </div>
        <div class="form-group">
            <?= Html::button('<i class="far fa-save"></i> Сохранить', ['id' => (isset($model->id) ? 'btnSubmitUpdate' : 'btnSubmitCreate'), 'class' => 'btn btn-success']) ?>
        </div>
        <? ActiveForm::end()?>
    <? Pjax::end()?>
</div>
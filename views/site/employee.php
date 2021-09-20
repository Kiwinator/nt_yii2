<?
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\widgets\Pjax;
	use yii\bootstrap4\Modal;
	
	$this->registerJsFile('/web/js/main.js', ['depends' => [\yii\web\JqueryAsset::classname()]]);

	$this->title = 'Тестовое задание: Сотрудники';
	
	$id_modal_create='modal_create_employee';
	Modal::begin([
	    'title' => 'Создание сотрудника',
	    'id' => $id_modal_create,
	    'size' => 'modal-lg',
	]);
	Modal::end();
	
	$id_modal_update='modal_update_employee';
	Modal::begin([
	    'title' => 'Изменение сотрудника',
	    'id' => $id_modal_update,
	    'size' => 'modal-lg',
	]);
	Modal::end();
	
	$id_modal_delete = 'modal_delete_employee';
	Modal::begin([
	    'title' => 'Удаление сотрудника',
	    'id' => $id_modal_delete,
	    'footer' =>
	        Html::button('Удалить',['class' => 'btn btn-outline-danger btn-sm','id'=>'delete-confirm-btn']).
	        Html::button('Отмена',['class' => 'btn btn-light btn-sm','data-dismiss'=>'modal'])
	]);
	Modal::end();
	
	echo Html::button('Создать', ['class' => 'btn btn-success mb-2','onclick'=>"show_modal('".$id_modal_create."','POST','employee-create','')"]);
	Pjax::begin(['id' => 'employee_grid_pjax', 'timeout' => false, 'options' => ['class' => 'pjax-loader']]);
	echo GridView::widget([
		'dataProvider' => $dataProvider,
	    'filterModel' => $searchModel,
	    'layout' => '{items}{pager}',
		'columns' => [
			'id',
			'name',
			'department',
            [
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:120px'],
                'contentOptions' => [
                    'class' => ['text-center','align-middle']
                ],
                'value' => function($data) use ($id_modal_delete, $id_modal_update){
                    $item='
                    <div class="d-flex justify-content-center">
                    	'.Html::button('Изменить',['class' => 'btn btn-outline-primary btn-sm', 'title'=>'Изменить', 
							'data-placement' => 'top', 'data-toggle'=>"tooltip", 'onclick'=>"show_modal('".$id_modal_update."','POST','employee-update','id=".$data['id']."')"]).' 
						'.Html::button('Удалить',['class' => 'btn btn-outline-danger btn-sm btn_delete_modal', 'title'=>'Удалить', 'data-pjax_container' => 'employee_grid_pjax',
							'data-placement' => 'top', 'data-toggle'=>"tooltip", 'data-id_modal' => $id_modal_delete, 'data-path' => 'employee-deleting', 'data-id' => Html::encode($data['id'])])."
					</div>";
					return $item;
                }
            ],
		],
	]); 
	Pjax::end();
	$this->registerJs(
        '$("document").ready(function(){
            $("#search-form").on("pjax:end", function() {
                $.pjax.defaults.timeout = false;
                $.pjax.reload({container:"#employee_grid_pjax"});
            });
        });'
    );
?>
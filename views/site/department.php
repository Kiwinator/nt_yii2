<?
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\widgets\Pjax;
	use yii\bootstrap4\Modal;
	
	$this->registerJsFile('/web/js/main.js', ['depends' => [\yii\web\JqueryAsset::classname()]]);

	$this->title = 'Тестовое задание: Отделы';
	
	$id_modal_create='modal_create_department';
	Modal::begin([
	    'title' => 'Создание отдела',
	    'id' => $id_modal_create,
	    'size' => 'modal-lg',
	]);
	Modal::end();
	
	$id_modal_update='modal_update_department';
	Modal::begin([
	    'title' => 'Изменение отдела',
	    'id' => $id_modal_update,
	    'size' => 'modal-lg',
	]);
	Modal::end();
	
	$id_modal_delete = 'modal_delete_department';
	Modal::begin([
	    'title' => 'Удаление отдела',
	    'id' => $id_modal_delete,
	    'footer' =>
	        Html::button('Удалить',['class' => 'btn btn-outline-danger btn-sm','id'=>'delete-confirm-btn']).
	        Html::button('Отмена',['class' => 'btn btn-light btn-sm','data-dismiss'=>'modal'])
	]);
	Modal::end();
	echo Html::button('Создать', ['class' => 'btn btn-success mb-2','onclick'=>"show_modal('".$id_modal_create."','POST','department-create','')"]);
	Pjax::begin(['id' => 'department_grid_pjax', 'timeout' => false, 'options' => ['class' => 'pjax-loader']]);
	echo GridView::widget([
		'dataProvider' => $dataProvider,
	    'filterModel' => $searchModel,
	    'layout' => '{items}{pager}',
		'columns' => [
			'id',
			'name',
            [
                'format' => 'raw',
                'headerOptions' => ['style' => 'width:120px'],
                'contentOptions' => [
                    'class' => ['text-center','align-middle']
                ],
                'value' => function($data) use ($id_modal_delete, $id_modal_update, $notDelete){
                    $item='
                    <div class="d-flex justify-content-center">
                    	'.Html::button('Изменить',['class' => 'btn btn-outline-primary btn-sm', 'title'=>'Изменить', 
							'data-placement' => 'top', 'data-toggle'=>"tooltip", 'onclick'=>"show_modal('".$id_modal_update."','POST','department-update','id=".$data['id']."')"]);
					if (!in_array($data['id'], $notDelete))
						$item.=Html::button('Удалить',['class' => 'btn btn-outline-danger btn-sm btn_delete_modal', 'title'=>'Удалить', 'data-pjax_container' => 'department_grid_pjax',
							'data-placement' => 'top', 'data-toggle'=>"tooltip", 'data-id_modal' => $id_modal_delete, 'data-path' => 'department-deleting', 'data-id' => Html::encode($data['id'])]);
					$item.="</div>";
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
                $.pjax.reload({container:"#department_grid_pjax"});
            });
        });'
    );
?>
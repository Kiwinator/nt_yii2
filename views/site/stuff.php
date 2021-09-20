<?php
	use yii\helpers\Html;
	use yii\grid\GridView;
	
	$this->title = 'Тестовое задание: Сотрудники в отделах';
	echo GridView::widget([
		'dataProvider' => $dataProvider,
	    'filterModel' => $searchModel,
	    'layout' => '{items}{pager}',
		'columns' => [
			'employee_name',
            'department_name'
		],
	]);
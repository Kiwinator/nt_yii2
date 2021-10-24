<?php

	use kartik\select2\Select2;
    use yii\web\JsExpression;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\widgets\Pjax;
	use yii\grid\GridView;

	/* @var $this yii\web\View */

	$this->title = 'Тестовое задание: Главная страница';
	$this->registerJsFile('/web/js/main.js', ['depends' => [\yii\web\JqueryAsset::classname()]]);
?>
<div class="site-index main-background">
    <div class="filter-image-background">
    	<div class="filter-content">
    		<p class="filter-content-text filter-content-text-main">Netwrix Partner Locator</p>
    		<p class="filter-content-text filter-content-text-sub">Hundreds of Netwrix partners around the world are standing by to help you.<br>With our Partner Locator you can easily find the list of authorized partners in your area.</p>
    		<div class="filter-container-search">
    			<?=Html::textInput('search','',['id'=>'search-input','class'=>'search-trigger filter-container-search-input','placeholder'=>'Search by company name or adress...', 'autocomplete'=>false,])?>
    			<button class="filter-container-search-button"><i class="fas fa-search"></i></button>
    		</div>
    		<div class="filter-container-ddl">
    			<div class="filter-container-ddl-main">
    				<?=Select2::widget([
					    'name' => 'type',
					    'data' => $typeArr,
					    'options' => [
					        'placeholder' => 'Type',
					        'id'=>'customer-type',
					        'class'=> 'search-trigger',
					    ],
					])?>
    			</div>
    			<div class="filter-container-ddl-sub filter-left">
    				<?=Select2::widget([
					    'name' => 'country',
					    'data' => $countryArr,
					    'options' => [
					        'placeholder' => 'Country',
					        'id'=>'customer-country',
					        'class'=> 'search-trigger search-trigger-country',
					    ],
					])?>
				</div>
				<div class="filter-container-ddl-sub filter-right">
					<?=Select2::widget([
					    'name' => 'state',
					    'options' => [
					        'placeholder' => 'State',
					        'id'=>'customer-state',
					        'disabled' => true,
					        'class'=> 'search-trigger',
					    ],
					])?>
				</div>
			</div>
    	</div>
    </div>
    <div class="result-container">
    	<?Pjax::begin(['id' => 'customer_pjax', 'timeout' => false, 'options' => ['class' => 'pjax-loader']])?>
	   		<?=GridView::widget([
				'dataProvider' => $dataProvider,
			    'filterModel' => $searchModel,
			    'showHeader'=> false,
			    'layout' => '{items}{pager}',
			    'emptyText' => '<div class="content-center">Your search parameters did not match any partners. Please try different search.</div>',
				'columns' => [
					[
			        	'format' => 'raw',
			    	    'value' => function($data) use ($typeArr){
			    	    	$logo = '';
			    	    	if ($data['logo'] != '') {
				               	$logo='<img src="/images/'.$data['logo'].'" class="content-img" alt="">';
				    	    }
							$item='
		                    <div class="result-container-main">
		                    	<div class="result-container-sub">
				                    <div id="logo" class="col-md-2 result-content-center">'.$logo.'</div>
					                <div id="name_address" class="col-md-6">
										<p class="result-content result-content-top result-content-max">'.$data['company'].'</p>
					              		<p class="result-content result-content-min result-content-middle">'.$data['address'].'</p>
					               	</div>
					                <div id="info" class="col-md-2">
					              		<p class="result-content result-content-top result-content-middle">
					              			<a href="'.$data['website'].'" target="_blank">Website</a>
					              		</p>
					               		<p class="result-content">'.$data['phone'].'</p>
					               	</div>
			                    	<div id="status" class="col-md-2">
					                    <p class="result-content">'.$typeArr[$data['status']].'</p>
					            	</div>
				            	</div>
							</div>';
							return $item;
						}
					],
				],
			]); ?>
    	<?Pjax::end()?>
    </div>
</div>
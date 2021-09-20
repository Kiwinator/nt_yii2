<?php

	/* @var $this yii\web\View */

	$this->title = 'Тестовое задание: Главная страница';
?>
<div class="site-index">
    <div class="body-content">
        <div class="row row_20">
            <div class="col-lg-4">
                <h2>Отделы</h2>
                <div class="index_middle_part">
                	<p>Список отделов: управление отделами - создание, редактирование и удаление.</p>
                </div>
                <p><a class="btn btn-outline-secondary" href="/department">Отделы &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Сотрудники</h2>
                <div class="index_middle_part">
                <p>Список сотрудников: управление сотдруниками - создание, редактирование, удаление и назначение в отделы.</p>
                </div>
                <p><a class="btn btn-outline-secondary" href="/employee">Сотрудники &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Сотрудники в отделах</h2>
                <div class="index_middle_part">
                	</div>
                <p><a class="btn btn-outline-secondary" href="/department-stuff">Сотрудники в отделах &raquo;</a></p>
            </div>
        </div>
    </div>
</div>
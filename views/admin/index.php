<?php

use app\base\widgets\ActionColumn\AdminActionColumn;
use nagser\base\widgets\ActionColumn\ActionColumn;
use nagser\base\widgets\GridView\GridView;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/** @var $dataProvider yii\data\ArrayDataProvider **/

$this->title = Yii::t('themes', 'Themes');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'preview',
            'label' => Yii::t('themes', 'Preview'),
            'format' => 'html',
            'headerOptions' => [
                'style' => 'width: 220px;',
            ],
            'options' => ['style' => 'vertical-align: middle'],
            'value' => function($model){
                return Html::img(Yii::$app->request->baseUrl . '/themes/' . ArrayHelper::getValue($model, 'dir') . '/preview.png', [
                    'class' => 'img-responsive',
                ]);
            },
        ],
        [
            'attribute' => 'name',
            'label' => Yii::t('themes', 'Name'),
            'vAlign' => GridView::ALIGN_MIDDLE
        ],
        [
            'attribute' => 'link',
            'label' => Yii::t('themes', 'Link to description'),
            'value' => function($model){
                return Html::a(Yii::t('themes', 'Description'), ArrayHelper::getValue($model, 'link'), ['class' => 'btn btn-default btn-sm', 'target' => '_blank']);
            },
            'format' => 'raw',
            'vAlign' => GridView::ALIGN_MIDDLE
        ],
        [
            'class' => ActionColumn::className(),
            'template' => '{copy} {delete}',
            'buttons' => [
                'copy' => function($url, $model){
                    return Html::a(Html::tag('i', '', ['class' => 'fa fa-files-o']), $url, [
                        'class' => 'btn btn-default btn-sm jsOpen',
                        'data-title' => Yii::t('themes', 'Copy theme'),
                    ]);
                },
            ],
        ]
    ],
])?>
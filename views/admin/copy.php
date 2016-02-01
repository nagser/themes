<?php

/** @var $model app\modules\themes\models\ThemesModel**/
use kartik\form\ActiveForm;

?>

<? $form = ActiveForm::begin([
    'id' => 'themeCloneForm',
    'enableAjaxValidation' => true,
])?>

<?= $form->field($model, 'name')->textInput()?>
<?= $form->field($model, 'dir')->textInput()?>

<?= \yii\helpers\Html::button(Yii::t('themes', 'Copy theme'), ['class' => 'btn btn-alert btn-block', 'type' => 'submit'])?>

<? ActiveForm::end()?>

<?php

namespace nagser\themes;

use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;

class Bootstrap implements BootstrapInterface {

    private $_modelMap = [
        'ThemesRecord' => 'nagser\themes\models\ThemesRecord',
    ];

    public function bootstrap($app){
        /**@var Module $module**/
        $module = $app->getModule('themes');
        $this->_modelMap = ArrayHelper::merge($this->_modelMap, $module->modelMap);
        foreach ($this->_modelMap as $name => $definition) {
            $class = "nagser\\themes\\models\\" . $name;
            \Yii::$container->set($class, $definition);
            $modelName = is_array($definition) ? $definition['class'] : $definition;
            $module->modelMap[$name] = $modelName;
        }
        //Загрузка языков
        if (!isset($app->get('i18n')->translations['themes'])) {
            $app->get('i18n')->translations['themes'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@app/vendor/nagser/themes/messages',
                'fileMap' => ['themes' => 'themes.php']
            ];
        }
    }

}
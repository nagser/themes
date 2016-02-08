<?php

namespace nagser\themes\components;

use nagser\base\helpers\FileHelper;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class ThemeConfigurator extends Component {

    private $currentThemeConfig;

    public function init(){
        parent::init();
        $this->currentThemeConfig = $this->getCurrentThemeConfig();
        $this->loadDependencies();
    }

    /**
     * Получение конфигурации текущей темы
     * */
    private function getCurrentThemeConfig(){
        return FileHelper::requireFile(\Yii::getAlias('@currentThemePath') . '/config.php');
    }

    /**
     * Настройка контейнеров внедрения зависимостей
     * */
    private function loadDependencies(){
        $dependencies = ArrayHelper::getValue($this->currentThemeConfig, 'dependencies', []);
        if(!$dependencies) return;
        foreach ($dependencies as $class => $dependency) {
            \Yii::$container->set($class, $dependency);
        }
    }

}
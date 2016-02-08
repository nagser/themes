<?php

namespace nagser\themes\models;

use nagser\base\helpers\FileHelper;
use nagser\base\models\Model;
use Yii;
use yii\web\NotFoundHttpException;

class ThemesRecord extends Model
{

    /**
     * Название темы
     * */
    public $name;
    /**
     * Директория с темой(выполняет роль id)
     * */
    public $dir;
    /**
     * Ссылка на описание темы
     * */
    public $link;
    public $additional;

    public function rules()
    {
        return [
            [['name', 'dir', 'link'], 'string'],
            [['name', 'dir'], 'required'],
            ['dir', function ($attribute, $params) {
                array_search($this->$attribute, $this->getThemesList()) and $this->addError($attribute, Yii::t('themes', 'Theme directory "{directory}" already exist', ['directory' => $this->$attribute]));
            }]
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('themes', 'Name'),
            'dir' => Yii::t('themes', 'Dir'),
            'link' => Yii::t('themes', 'Description'),
        ];
    }

    /**
     * Получить список тем в директории themes
     * @return array
     * */
    public function getThemesList()
    {
        $dirs = scandir(\Yii::getAlias('@themes'));//Сканируем директорию с темами
        unset($dirs[0], $dirs[1]);//удаляем ссылки на пред.уровень
        return $dirs;
    }

    /**
     * Получение датапровайдера на основе конфигов тем.
     * Для нормальной работы в директории с темой должен быть корректный config.php
     * @return array
     * */
    public function getThemeProvider()
    {
        $data = [];
        $dirs = $this->getThemesList();
        foreach ($dirs as $dir) {
            $data[] = FileHelper::requireFile(\Yii::getAlias('@themes') . '/' . $dir . '/config.php');//Считываем конфиг темы
        }
        return $data;
    }

    /**
     * @param $id string
     * @return array
     */
    public function getThemeDirs($id)
    {
        $dirs = scandir($this->getThemeDir($id));
        unset($dirs[0], $dirs[1]);
        return $dirs;
    }

    public function getThemeDir($id)
    {
        return \Yii::getAlias('@themes') . '/' . $id;
    }

    /**
     * Найти тему по $id($id - это диретория с темой)
     * @param $id string
     * @return $this object
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     */
    public function findRecordModel($id)
    {
        $config = FileHelper::requireFile(\Yii::getAlias('@themes') . '/' . $id . '/config.php');
        if ($this->load(['theme' => $config], 'theme')) {
            return $this;
        } else {
            throw new NotFoundHttpException;
        }
    }

    /**
     * Клонирование темы оформления(директория и конфигурация)
     * @param $id string
     * */
    public function copy($id)
    {
        $srcDirs = $this->getThemeDirs($id);//список директорий копируемой темы
        foreach ($srcDirs as $item) {
            $fullPathForItem = $this->getThemeDir($id) . '/' . $item; //директория которую будем копировать
            if (is_dir($fullPathForItem)) {
                $destDir = $this->getThemeDir($this->dir) . '/' . $item;
                FileHelper::copyDirectory($fullPathForItem, $destDir); //Копируем директорию
            } else {
                $destFile = $this->getThemeDir($this->dir) . '/' . $item;
                copy($fullPathForItem, $destFile);//Копируем файл
            }
        }
        //После завершения копирования редактируем конфиг
        $config = $this->getThemeDir($this->dir) . '/config.php';
        file_put_contents($config, '<?php  return ' . var_export($this->attributes, true) . ';');
    }


    /**
     * Удаление темы оформления
     * */
    public function delete()
    {
        FileHelper::removeDirectory($this->getThemeDir($this->dir));
    }
}
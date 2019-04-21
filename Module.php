<?php

namespace panix\mod\votes;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\web\GroupUrlRule;
use panix\engine\WebModule;
use panix\mod\user\models\forms\SettingsForm;

class Module extends WebModule implements BootstrapInterface
{



    public function getAdminMenu()
    {
        return [
            'user' => [
                'label' => 'Пользователи',
                'icon' => $this->icon,
                'items' => [
                    [
                        'label' => Yii::t('user/admin', 'Users'),
                        "url" => ['/user'],
                        'icon' => $this->icon
                    ],
                    [
                        'label' => Yii::t('app', 'SETTINGS'),
                        "url" => ['/user/settings'],
                        'icon' => 'settings'
                    ]
                ],
            ],
        ];
    }

    public function getAdminSidebar()
    {
        return (new \panix\engine\bootstrap\BackendNav)->findMenu($this->id)['items'];
    }

    /**
     * Установка модуля
     * @return boolean
     */
    public function afterInstall()
    {
        Yii::$app->db->import($this->id);

        if (Yii::$app->settings)
            Yii::$app->settings->set($this->id, SettingsForm::defaultSettings());
        return parent::afterInstall();
    }

    /**
     * Удаление модуля
     * @return boolean
     */
    public function afterUninstall()
    {

        Yii::$app->settings->clear($this->id);
        return parent::afterUninstall();
    }

    public function getInfo()
    {
        return [
            'label' => Yii::t('user/default', 'MODULE_NAME'),
            'author' => 'andrew.panix@gmail.com',
            'version' => '1.0',
            'icon' => 'icon-users',
            'description' => Yii::t('user/default', 'MODULE_DESC'),
            'url' => ['/user'],
        ];
    }

    /**
     * @inheritdoc
     * NOTE: THIS IS NOT CURRENTLY USED.
     *       This is here for future versions and will need to be bootstrapped via config file
     *
     */
    public function bootstrap($app)
    {
        // add rules for admin/copy/auth controllers
        $groupUrlRule = new GroupUrlRule([
            'prefix' => $this->id,
            'rules' => [
                '<controller:(admin|copy|auth)>' => '<controller>',
                '<controller:(admin|copy|auth)>/<action:\w+>' => '<controller>/<action>',
                '<action:\w+>' => 'default/<action>',
            ],
        ]);
        $app->getUrlManager()->addRules($groupUrlRule->rules, false);
    }


}

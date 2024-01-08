<?php

declare(strict_types=1);

namespace Plugin\Landswitcher;

use JTL\Shop;
use JTL\Shopsetting;
use JTL\Router\Router;
use JTL\Smarty\JTLSmarty;
use JTL\Events\Dispatcher;
use JTL\Plugin\Bootstrapper;
use Plugin\Landswitcher\Model\TLandModel;
use Plugin\Landswitcher\Manager\NonceManager;
use Plugin\Landswitcher\Model\TLandswitcherRedirectsModel;
use Plugin\Landswitcher\Controller\Form\SettingsFormController;

class Bootstrap extends Bootstrapper
{
    private Router $router;

    public function boot(Dispatcher $dispatcher): void
    {
        parent::boot($dispatcher);

        $dispatcher->hookInto(HOOK_ROUTER_PRE_DISPATCH, function ($args) {
            $config = Shopsetting::getInstance()->getAll();
            $config['link'] = $this->getPlugin()->getLinks()->getLinks()->first();
            $this->router = $args['router'];

            $controller = new SettingsFormController(
                $this->getDB(),
                $this->getCache(),
                $this->router->getState(),
                $config,
                Shop::Container()->getAlertService()
            );

            $this->router->addRoute('/landswitcher/settingsave', [$controller, 'save'], 'settingsave', ['POST']);
        });
    }

    public function renderAdminMenuTab(string $tabName, int $menuID, JTLSmarty $smarty): string
    {
        $currentLanguage = 'cDeutsch';

        if (isset($_SESSION['AdminAccount']->language)) {
            $currentLanguage = $_SESSION['AdminAccount']->language == 'en-GB' ? 'cEnglisch' : 'cDeutsch';
        }

        $landModel = new TLandModel();

        $countries = Shop::Container()->getDB()->selectAll(
            $landModel->getTableName(),
            [],
            [],
            "*",
        );

        $redirectModel = new TLandswitcherRedirectsModel();

        $redirects = Shop::Container()->getDB()->selectAll(
            $redirectModel->getTableName(),
            [],
            [],
            '*',
        );

        $nonce = new NonceManager();
        $mainJsPath = $this->getPlugin()->getPaths()->getAdminURL() . 'Script/Main.js';
        $mainCssPath = $this->getPlugin()->getPaths()->getAdminURL() . 'Style/Main.css';
        $smarty->assign('nonceField', $nonce->createField('settings_form_nonce'));
        $smarty->assign('countries', $countries);
        $smarty->assign('redirects', $redirects);
        $smarty->assign('currentLanguage', $currentLanguage);
        $smarty->assign('mainJsPath', $mainJsPath);
        $smarty->assign('mainCssPath', $mainCssPath);
        $templatePath = $this->getPlugin()->getPaths()->getAdminPath() . '/Template/Settings.tpl';
        return $smarty->fetch($templatePath);
    }
}

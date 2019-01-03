<?php

namespace App\Presenters;

use Nette;


class BasePresenter extends Nette\Application\UI\Presenter {
    
    /** @var \h4kuna\Gettext\GettextSetup */
    protected $translator = null;

    /**
     * Inject translator
     * @param $translator \h4kuna\Gettext\GettextSetup
     */
    public function injectTranslator(\h4kuna\Gettext\GettextSetup $translator) {
        $this->translator = $translator;
    }
    
    /** 
     * 
     * @return \h4kuna\Gettext\GettextSetup 
     */
    public function getTranslator(): \h4kuna\Gettext\GettextSetup {
        return($this->translator);
    }

    /**
     * Items of the menu [$link => [name, description], ...]
     * @var Array
     */
    protected $menu = array();

    protected function startup() {
        parent::startup();
        $this->lang = $this->translator->setLanguage($this->lang);
    }
    
    protected function beforeRender() {
        $this->template->baseUri = BASE_URI;
        $this->template->setTranslator($this->translator);
    }
    
    public function createTemplate($class = NULL) {
        $template = parent::createTemplate($class);
        // if not set, the default language will be used
        if (!isset($this->lang)) {
            $this->lang = $this->translator->getLanguage();
        } else {
            if (!in_array($this->lang, ['cs', 'en'])) {
                $this->lang = 'cs';
            }
            $this->translator->setLanguage($this->lang);
        }
        $template->setTranslator($this->translator);
        return $template;
    }

    
    public function setLang(string $lang): void {
        $this->lang = $lang;
        // Uložení do session
        $section = $this->session->getSection('default');
        $section->myLang = $this->lang;
    }

    public function getLang(): string {
        if (is_null($this->lang)) {
            // Není - musíme jít do cache
            $section = $this->session->getSection('default');
            $this->lang = isset($section->myLang) ? $section->myLang : null;
            if (is_null($this->lang)) {
                // Nenalezli jsme - vezmeme default z konfigu
                $params = $this->context->getParameters();
                $this->lang = isset($params['lang']) ? $params['lang'] : 'cs';
            }
        }
        return($this->lang);
    }

    public function isLogged():bool {
        if ($this->user instanceOf NUser) {
            return($this->user->isLoggedIn());
        } else {
            return(false);
        }
    }

    /** @persistent */
    public $lang;
    
    public $freePage = false;
    public function isFreePage(): bool {
        return($this->freePage);
    }

    public function actionSetLang(?string $lang = null, ?string $backlink = null): void {
        $this->setLang($lang);
        if (is_null($backlink)) {
            $this->redirect('default');
        } else {
            $this->redirect($backlink);
        }
    }

    
    
    
    
    
    /** @var \WebLoader\Nette\LoaderFactory @inject */
    public $webLoader;

    /** @return CssLoader */
    protected function createComponentCss() {
        return($this->webLoader->createCssLoader('css'));
    }
    
    /** @return CssLoader */
    protected function createComponentCssPrint() {
        return($this->webLoader->createCssLoader('cssPrint'));
    }
    
    /** @return CssLoader */
    protected function createComponentJs() {
        return($this->webLoader->createJavaScriptLoader('js'));
    }
    
    /** @return JsLoader */
    protected function createComponentJQuery() {
        return $this->webLoader->createJavaScriptLoader('jQuery');
    }

}

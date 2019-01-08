<?php

namespace App\Presenters;

use Nette;


class HomepagePresenter extends BasePresenter {

    
    //==========================================================================
    // Twitter
    //==========================================================================
    /** @var \h4kuna\Gettext\GettextSetup */
    protected $twitter = null;

    /**
     * Inject translator
     * @param $twitter \TwitterAPIExchange
     */
    public function injectTwitter(\TwitterAPIExchange $twitter) {
        $this->twitter = $twitter;
    }
    
    protected function createComponentTwitterTimeLine() {
        return new \Controls\TwitterTimeLine($this, 'twitterTimeLine', $this->twitter);
    }
}

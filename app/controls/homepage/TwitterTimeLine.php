<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controls;

/**
 * Description of TwitterTimeLine
 *
 * @author u935
 */
class TwitterTimeLine extends \Nette\Application\UI\Control {
    /** var \TwitterAPIExchange */
    protected $twitter = null;
    public function __construct($parent = null, string $name = '', \TwitterAPIExchange $twitter = null) {   
        parent::__construct();
        if (empty($name)) {
            $name = 'twitterTimeLine';
        }
        $this->twitter = $twitter;
        $parent->addComponent($this, $name);
    }
    
    public function render() {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $getfield = '?screen_name=Vlk_Honzi';
        $requestMethod = 'GET';
        $response = $this->twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();
        
        $this->template->setFile(dirname(__FILE__) . '/TwitterTimeLine.latte');
        $response = json_decode($response);
        //var_dump(json_decode($response));
        if (is_array($response)) {
            $this->template->tweets = $response;
        } else {
            $this->template->tweets = [];
        }
        foreach($this->template->tweets as $key => $tweet) {
            //\Tracy\Debugger::dump($tweet); exit;
            $this->template->tweets[$key]->created_at_dt = new \DateTime($tweet->created_at);
        }
        $this->template->render();

    }

}

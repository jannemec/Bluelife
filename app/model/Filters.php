<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model;

/**
 * Description of Filters
 *
 * @author u935
 */
class Filters {
    //put your code here
    use \Nette\SmartObject;
    /**
     * loader
     * @param filtr name
     * @return mixed
     */
    public static function loader($filter) {
        return (method_exists(__CLASS__, $filter) ? call_user_func_array([__CLASS__, $filter], array_slice(func_get_args(), 1)) : null);
    }

    /**
     * toUTF
     * @param string
     * @return Nette\Utils\Html
     */
    public static function toUTF($s) {
        return(iconv("CP1250", "UTF-8", $s));
    } 
    
    /**
     * dt
     * @param DateTime
     * @return Nette\Utils\Html
     */
    public static function dt(\DateTime $s) {
        return($s->format('j.n.Y H:i'));
    }

}
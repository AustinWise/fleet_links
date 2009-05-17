<?php

/**
 * @see Zend_Controller_Action
 */
require_once 'Zend/Controller/Action.php';


/**
 * @category   Zend
 * @package    Zend_OpenId
 * @subpackage Demos
 * @uses       Zend_Controller_Action
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ErrorController extends Zend_Controller_Action
{
    /**
     * indexAction
     *
     * @return void
     */
    public function errorAction() 
    {
      $this->view->errorDump = var_dump($this->_getParam('error_handler'));
    }
}
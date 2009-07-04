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
class LinkController extends Zend_Controller_Action
{
    /**
     * indexAction
     *
     * @return void
     */
    public function indexAction() 
    {
        $fleet = new Default_Model_Fleet();
        $this->view->entries = $fleet->getFleetsForAlliance(824518128);
    }
    
    public function addAction() 
    {
        $form    = new Default_Form_Fleet();
        // Check to see if this action has been POST'ed to.
        if ($this->getRequest()->isPost()) {

            // Now check to see if the form submitted exists, and
            // if the values passed in are valid for this form.
            if ($form->isValid($request->getPost())) {

                // Since we now know the form validated, we can now
                // start integrating that data sumitted via the form
                // into our model:
                $model = new Default_Model_Fleet($form->getValues());
                $model->save();

                // Now that we have saved our model, lets url redirect
                // to a new location.
                // This is also considered a "redirect after post";
                // @see http://en.wikipedia.org/wiki/Post/Redirect/Get
                return $this->_helper->redirector('index');
            }
        }
        $this->view->form = $form;
        //$fleet = new Default_Model_Fleet();
        //$this->view->entries = $fleet->getFleetsForAlliance(824518128);
    }
}
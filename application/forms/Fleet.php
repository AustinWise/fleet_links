<?php

class Default_Form_Fleet extends Zend_Form
{
    /**
     * init() is the initialization routine called when Zend_Form objects are 
     * created. In most cases, it make alot of sense to put definitions in this 
     * method, as you can see below.  This is not required, but suggested.  
     * There might exist other application scenarios where one might want to 
     * configure their form objects in a different way, those are best 
     * described in the manual:
     *
     * @see    http://framework.zend.com/manual/en/zend.form.html
     * @return void
     */ 
    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');

        // Add a place to put the fleet link
        $this->addElement('text', 'fleetlink', array(
            'label'      => 'The fleet link:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));

        // Add a name element
        $this->addElement('text', 'name', array(
            'label'      => 'Name of the fleet:',
            'required'   => true,
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
            )
        ));

        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Add fleet',
        ));

        // some CSRF protection
        $this->addElement('hash', 'csrf', array(
            'ignore' => true,
        ));
    }
}

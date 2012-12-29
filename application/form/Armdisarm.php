<?php

class Form_Armdisarm extends Zend_Form
{
    public function init ()
    {
        $this->setName("armdisarm");
        $this->setMethod('post');

        $this->addElement('text', 'reason',
                array(
                        'filters' => array(
                                'StringTrim'
                        ),
                        'validators' => array(
                                array(
                                        'StringLength',
                                        false,
                                        array(
                                                0,
                                                50
                                        )
                                )
                        ),
                        'required' => false,
                        'label' => 'Reason:'
                )
        );
        
        $this->addElement('submit', $this->getAttrib('submitLablel'),
                array(
                        'required' => false,
                        'ignore' => true,
                        'label' => $this->getAttrib('submitLablel')
                )
        );
    }
}
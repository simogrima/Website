<?php
namespace OmniBlog\Form;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Form;

class PostForm extends Form
{
    public function __construct(ObjectManager $objectManager){
        parent::__construct('post-form');
       
        // The form will hydrate an object of type "Post"
        $this->setHydrator(new DoctrineHydrator($objectManager, 'OmniBlog\Entity\Post', true));
        
        // Add the user fieldset, and set it as the base fieldset
        $postFieldset = new PostFieldset($objectManager);
        $postFieldset->setUseAsBaseFieldset(true);
        $this->add($postFieldset);
        
        // … add CSRF and submit elements …
        
        // Optionally set your validation group here

        
        $this->add(array(
        		'type' => 'Zend\Form\Element\Csrf',
        		'name' => 'csrf'
        ));
       
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Send'
            )
        ));
    }
}
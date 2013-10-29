<?php
namespace OmniBlog\Form;

use OmniBlog\Entity\Post;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use OmniBlog;

class PostFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct(ObjectManager $objectManager)
    {
        parent::__construct('post');
        $this->setHydrator(new DoctrineHydrator($objectManager,'OmniBlog\Entity\Post', true))
             ->setObject(new Post());

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
    		'name' => 'id'
        ));
        
        $this->add(array(
            'type'    => 'Zend\Form\Element\Text',
    		'name' => 'title',
    		'options' => array(
				'label' => 'Name of Post'
    		),
    		'attributes' => array(
				'required' => 'required'
    		)
        ));
        
        $this->add(array(
            'type'    => 'Zend\Form\Element\Text',
    		'name' => 'author',
    		'options' => array(
				'label' => 'Author of Post'
    		),
    		'attributes' => array(
				'required' => 'required'
    		)
        ));
        
        $this->add(array(
            'type'    => 'Zend\Form\Element\Text',
    		'name' => 'content',
    		'options' => array(
				'label' => 'Content of Post'
    		),
    		'attributes' => array(
				'required' => 'required'
    		)
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\DateTimeSelect',
            
    		'name' => 'postedAt',
    		'attributes' => array(
				'required' => 'required'
    		)
        ));
        //$objectManager->getRepository('OmniBlog\Form\CategoryPostAssociation')->findBy(array('post_id' => $this->po))
        //$this->getEntityManager()->find('OmniBlog\Entity\Post', $id);
        //DoctrineModule\Form\Element\ObjectMultiCheckbox $xmy = new \DoctrineModule\Form\Element\ObjectMultiCheckbox();

        $categoryFieldset = new CategoryFieldset($objectManager);
        $this->add(array(
            'type'    => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
            'name' => 'categories',
    		'options' => array(
				'label' => 'Select Categories',
    		    'object_manager' => $objectManager,
    		    'should_create_template' => true,
        		'target_class'   => 'OmniBlog\Entity\Category',
    		    'property'       => 'title',
				'target_element' => $categoryFieldset,
    		  ),
        ));
        //$element->setValue(array('1111','2222');
        /*$linkFieldset = new OmniBlog\Form\CategoryPostAssociationFieldset($objectManager);
        $this->add(array(
        		'type'    => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
        		'name' => 'category_post_associations',
        		'options' => array(
        				'label' => 'Select Categories',
        				'object_manager' => $objectManager,
        				'should_create_template' => true,
        				'target_class'   => 'OmniBlog\Entity\CategoryPostAssociation',
        				'target_element' => $linkFieldset,
        		        'property' => 'id',
        		),
        ));
        */
//         $categoryFieldset = new OmniBlog\Form\CategoryPostAssociationFieldset($objectManager);
//         $this->add(array(
//         		'type'    => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
//         		'name' => 'category_post_associations',
//         		'options' => array(
//         				'label' => 'Select Categories',
//         				'object_manager' => $objectManager,
//         				//'count' => 1,
//         				'should_create_template' => true,
//         				//'target_class'   => 'OmniBlog\Entity\Category',
//         		        'target_class'   => 'OmniBlog\Entity\CategoryPostAssociation',
//         		        //'property'       => 'category',
//         				'target_element' => $categoryFieldset,
//         				//'empty_item_label'   => '---',
//         		),
//         ));
        
// //         $categoryFieldset = new CategoryFieldset($objectManager);
// //         $this->add(array(
// //     		//'type' => 'Zend\Form\Element\Collection',
// //             //'type'    => 'DoctrineModule\Form\Element\ObjectSelect',
// //             'type'    => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
// //             'name' => 'categories',
// //     		'options' => array(
// // 				'label' => 'Select Categories',
// //     		    'object_manager' => $objectManager,
// // 				//'count' => 1,
// //     		    'should_create_template' => true,
// //         		'target_class'   => 'OmniBlog\Entity\Category',
// //     		    'property'       => 'title',
// // 				'target_element' => $categoryFieldset,
// //     		    //'empty_item_label'   => '---',
// //     		  ),
// //         ));
        
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
    	return array(
    			'title' => array(
    					'required' => true
    			),
    	);
    }
    /*public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
            ),
            'price' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Float'
                    )
                )
            )
        );
    }*/
}
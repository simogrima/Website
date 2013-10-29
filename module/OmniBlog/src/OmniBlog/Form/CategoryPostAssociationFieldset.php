<?php
namespace OmniBlog\Form;

use OmniBlog\Entity\CategoryPostAssociation;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
/*use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
*/
class CategoryPostAssociationFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct(ObjectManager $objectManager)
	{
		parent::__construct('category');
		$this->setHydrator(new DoctrineHydrator($objectManager,'OmniBlog\Entity\CategoryPostAssociation', true))
		->setObject(new CategoryPostAssociation());

		$this->add(array(
            //'type' => 'Zend\Form\Element\Hidden',
		    'type' => 'Zend\Form\Element\Text',
            'name' => 'id'
        ));
        
		//$categoryFieldset = new CategoryFieldset($objectManager);
		//$this->add($categoryFieldset);
		/*$this->add(array(
				'type'    => 'Zend\Form\Element\Object',
				'name'    => 'title',
				'options' => array(
						'label' => 'Category'
				)
		));
		$categoryFieldset = new CategoryFieldset($objectManager);
		$this->add(array(
				'type'    => 'DoctrineModule\Form\Element\ObjectMultiCheckbox',
				'name' => 'category_post_associations',
				'options' => array(
						'label' => 'Select Categories',
						'object_manager' => $objectManager,
						//'count' => 1,
						'should_create_template' => true,
						//'target_class'   => 'OmniBlog\Entity\Category',
						'target_class'   => 'OmniBlog\Entity\CategoryPostAssociation',
						'property'       => 'category',
						'target_element' => $categoryFieldset,
						//'empty_item_label'   => '---',
				),
		));
		*/
	}

	/**
	 * @return array
	 */
	public function getInputFilterSpecification()
	{
		return array(
            'id' => array(
                'required' => false
            )
        );
	}
}

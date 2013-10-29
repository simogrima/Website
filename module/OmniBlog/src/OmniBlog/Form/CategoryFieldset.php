<?php
namespace OmniBlog\Form;

use OmniBlog\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
/*use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;
*/
class CategoryFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct(ObjectManager $objectManager)
	{
		parent::__construct('category');
		$this->setHydrator(new DoctrineHydrator($objectManager,'OmniBlog\Entity\Category', true))
		->setObject(new Category());

		$this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Text',
            'name'    => 'title',
            'options' => array(
                'label' => 'Category'
            )
        ));
	}

	/**
	 * @return array
	 */
	public function getInputFilterSpecification()
	{
		return array(
            'id' => array(
                'required' => false
            ),

            'name' => array(
                'required' => true
            )
        );
	}
}
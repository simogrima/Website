<?php
/**
 * CategoryController
 *
 * @author Suiko6272
 *
 * @version 1.0.0
 *
 */
namespace OmniBlog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class CategoryController extends AbstractActionController
{
    protected $modelTable;
    private $tableRoute = 'OmniBlog\Model\CategoryTable';
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    
    public function setEntityManager(EntityManager $em)
    {
    	$this->em = $em;
    }
    
    public function getEntityManager()
    {
    	if (null === $this->em) {
    		$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    	return $this->em;
    }
    
    /**
     * The default action - show the home page
     */
    public function indexAction() {
    	 /*return new ViewModel(array(
        	'categories' => $this->getModelTable()->fetchAll()
        ));*/
        //return new ViewModel($entityManager);
        return new ViewModel(array(
            'categories' => $this->getEntityManager()->getRepository('\OmniBlog\Entity\Category')->findAll()
        ));

    }
    
    public function addAction() {
        //return new ViewModel();
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        //return new ViewModel($entityManager);
        return new ViewModel(array('em' => $entityManager));
    }
    
    public function editAction() {
    	return new ViewModel();
    }
    
    public function deleteAction() {
    	return new ViewModel();
    }
    
    public function getModelTable()
    {
    	if (!$this->modelTable) {
    		$sm = $this->getServiceLocator();
    		$this->modelTable = $sm->get($this->tableRoute);
    	}
    	return $this->modelTable;
    }
}
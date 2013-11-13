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
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use OmniBlog\Form\CategoryForm;

class CategoryController extends AbstractActionController
{
    const ROUTE_INDEX       = 'omni-blog';
    const ROUTE_CHILD       = 'omni-blog/blogchild';
    const ENTITY_PATH       = 'OmniBlog\Entity\Category';

    const CONTROLLER_NAME   = 'category';
    
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
    public function getRepository($parm) {
        return $this->getEntityManager()->getRepository($parm);
    }
    public function getRouteID(){
        return (int)$this->getEvent()->getRouteMatch()->getParam('id');
    }
    
    /**
     * The default action - show the home page
     */
    public function indexAction() {
        return new ViewModel(array(
            'categories' => $this->getEntityManager()->getRepository('\OmniBlog\Entity\Category')->findAll(),
            'latestPosts' => $this->latestPosts()
        ));

    }
    
    public function addAction() {
        $objectManager = $this->getEntityManager();
        
        $form = new CategoryForm($objectManager);
        $category = new \OmniBlog\Entity\Category();
        $form->bind($category);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        
        	if ($form->isValid()) {
        		$objectManager->persist($category);
        		$objectManager->flush();
        		return $this->redirect()->toRoute(
        		    static::ROUTE_CHILD,
        		    array('controller' => static::CONTROLLER_NAME
                ));
        	}
        }
        return new ViewModel(
            array('form' => $form)
        );
    }
    
    public function editAction() {
        $id = $this->getRouteID();
        if (!$id) {
        	return $this->redirect()->toRoute(
        	    static::ROUTE_CHILD,
        	    array('controller' => static::CONTROLLER_NAME,
        	           'action' => 'add'
        	   )
        	);
        }
    	
        
        $objectManager = $this->getEntityManager();
        
        $form = new CategoryForm($objectManager);
        //$category = new \OmniBlog\Entity\Category();
        $entity = $objectManager->find('OmniBlog\Entity\Category', $id);
        $form->bind($entity);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        
        	if ($form->isValid()) {
        		$objectManager->flush();
        		return $this->redirect()->toRoute(
        				static::ROUTE_CHILD,
        				array('controller' => static::CONTROLLER_NAME)
        		);
        	}
        }
        return new ViewModel(
            array('form' => $form, 'id' => $id)
        );
    }
    
    public function deleteAction() {
        $id = $this->getRouteID();
        if (!$id) {
        	return $this->redirect()->toRoute(
        			static::ROUTE_CHILD,
        			array('controller' => static::CONTROLLER_NAME,
        				    'action' => 'add'
        			)
        	   );
        }
        $entity = $this->getEntityManager()->find(static::ENTITY_PATH, $id);
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$del = $request->getPost()->get('del', 'No');
        	if ($del == 'Yes') {
        		//$id = (int)$request->getPost()->get('id');
        		//$entity = $this->getEntityManager()->find(static::ENTITY_PATH, $id);
        		if ($entity) {
        			$this->getEntityManager()->remove($entity);
        			$this->getEntityManager()->flush();
        		}
        	}
        
        	// Redirect to list of categories
        	return $this->redirect()->toRoute(
        			static::ROUTE_CHILD,
        			array('controller' => static::CONTROLLER_NAME)
    	       );
        }
        
        return new ViewModel(
            array(
        		'id' => $id,
        		'title' => $entity->getTitle() 
        ));
    }
    
    public function viewAction() {
        $id = $this->getRouteID();
        if (!$id) {
        	return $this->redirect()->toRoute(static::ROUTE_INDEX);
        }
        $entity = $this->getEntityManager()->find(static::ENTITY_PATH, $id);
    	return new ViewModel(
	       array('category' => $entity, 'categoryID' => $id)
    	);
    }
    public function latestPosts(){
        //'categories' => $this->getEntityManager()->getRepository('\OmniBlog\Entity\Category')->findAll()
        //$posts = $this->getRepository('\OmniBlog\Entity\Post')->findAll();

        $q = $this->getEntityManager()->createQuery(
            'SELECT p FROM OmniBlog\Entity\Post p ORDER BY p.postedAt DESC'
    	)->setMaxResults(3);
        
        //$q->setMaxResults()
        $posts = $q->getResult();
        //$posts = 
        return $posts;
    }
}
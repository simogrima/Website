<?php
/**
 * PostController
 *
 * @author Suiko6272
 *
 * @version 1.0.0
 *
 */
namespace OmniBlog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use OmniBlog\Form\PostForm;
use Doctrine\ORM\EntityManager;
use OmniBlog\Entity\Post;
//use Zend\Form\Element\MultiCheckbox;


/**
 * 
 * @author Suiko
 *
 */
class PostController extends AbstractActionController
{
    const ROUTE_INDEX       = 'omni-blog';
    const ROUTE_CHILD       = 'omni-blog/blogchild';
    const ENTITY_PATH       = 'OmniBlog\Entity\Post';

    const CONTROLLER_NAME   = 'category';
    
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;
    
    public function setEntityManager(EntityManager $em)
    {
    	$this->$entityManager = $em;
    }
    
    public function getEntityManager()
    {
    	if (null === $this->entityManager) {
    		$this->entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    	}
    	return $this->entityManager;
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
        	'posts' => $this->getRepository(static::ENTITY_PATH)->findAll()
        ));
    }

    public function addAction() {
        // Get your ObjectManager
        $objectManager = $this->getEntityManager();
        
        //Create the form and inject the ObjectManager
        //Bind the entity to the form
        $form = new PostForm($objectManager);
        $post = new Post();
        $form->bind($post);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        
        	if ($form->isValid()) {
        	    /*
        	    * Get IDs from form element
        	    * Get categories from the IDs
        	    * add entities to $post's categories list
        	    */
        	    $element = $form->getBaseFieldset()->get('categories'); //Object of: DoctrineModule\\Form\\Element\\ObjectMultiCheckbox
        	    $values = $element->getValue();

        	    foreach($values as $catID){
        	        $results = $objectManager->getRepository('OmniBlog\Entity\Category')->findBy(array('id' => $catID));
        	        $catEntity = array_pop($results);        	        
        	        $link = $post->addCategory($catEntity);
        	        //Entity/Post 's association table cascades persists and removes so don't need to persist($link), but would be done here
        	    }
        	    
         		$objectManager->persist($post);
         		$objectManager->flush();

        		return $this->redirect()->toRoute(
        		    static::ROUTE_CHILD,
        		    array('controller' => static::CONTROLLER_NAME
                ));
        	}
        }
        return array('form' => $form);
    }
    
    public function editAction() {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
        	return $this->redirect()->toRoute(
        			'omni-blog', array('controller' => 'post', 'action'=>'add'));
        }
        
        // Get your ObjectManager
        $objectManager = $this->getEntityManager();
        
        // Create the form and inject the ObjectManager
        $form = new PostForm($objectManager);
        
        //Get Entity by ID & bind to the form
        $post = $objectManager->find('OmniBlog\Entity\Post', $id);
        $form->bind($post);
        

        //Manage Associations here because couldn't find a way in fieldsets
        //Get Element 'categories'
        $element = $form->getBaseFieldset()->get('categories'); //Object of: DoctrineModule\\Form\\Element\\ObjectMultiCheckbox
        
        //Setup intial Associated_Categories
        $entity_links = $objectManager->getRepository('OmniBlog\Entity\CategoryPostAssociation')->findBy(array('post' => $post));
        $startingIDs = array();
        foreach($entity_links as $link){
            $catId = $link->getCategory()->getId();
            array_push($startingIDs, $catId);
        }
        $element->setValue($startingIDs);
        
        //Submit Button Pressed
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
            
        	if ($form->isValid()) {
        	    //random test: $form->bindOnValidate();
        	    
        	    //TODO: finish managing assocaitions
        	    $eValue = $element->getValue();

        	    $removeList = array_diff($startingIDs, $eValue);
        	    $addList = array_diff($eValue, $startingIDs);
        	    
        	    foreach($removeList as $removeId){
        	        foreach($entity_links as $link){
        	        	$cat = $link->getCategory();
        	        	if($cat->getId() == $removeId){
        	        	    $post->removeCategoryPostAssociations($link);
        	        	    $objectManager->remove($link);
        	        	}
        	        }
        	        
        	        
        	    }
        	    foreach($addList as $addId){
                    $category = $objectManager->find('OmniBlog\Entity\Category', $addId);
        	        $post->addCategory($category);
        	    }
        	    
        		// Save the changes
        		$objectManager->flush();
        		return $this->redirect()->toRoute('omni-blog',
        				array('controller' => 'post')
        		);
        	}
        }
        
        return array(
            'id' => $id,
            'form' => $form);
    }
    
    public function deleteAction() {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute(
                'omni-blog', array('controller' => 'post'));
        }
 
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $entity = $this->getEntityManager()->find('OmniBlog\Entity\Post', $id);
                if ($entity) {
                    $this->getEntityManager()->remove($entity);
                    $this->getEntityManager()->flush();
                }
            }
 
            // Redirect to list of posts
            return $this->redirect()->toRoute('omni-blog', array('controller' => 'post'));
        }
 
        return array(
            'id' => $id,
            'post' => $this->getEntityManager()->find('OmniBlog\Entity\Post', $id)
        );
    }
    public function viewAction() {
    	$id = $this->getRouteID();
    	if (!$id) {
    		return $this->redirect()->toRoute(static::ROUTE_INDEX);
    	}
    	$entity = $this->getEntityManager()->find(static::ENTITY_PATH, $id);
    	return new ViewModel(
    			array('post' => $entity, 'postID' => $id)
    	);
    }
}

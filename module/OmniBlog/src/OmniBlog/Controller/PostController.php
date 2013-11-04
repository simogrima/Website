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
use Zend\Form\Element\MultiCheckbox;


/**
 * 
 * @author Suiko
 *
 */
class PostController extends AbstractActionController
{
    protected $modelTable;
    private $tableRoute = 'OmniBlog\Model\PostTable';
    
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
    
    protected function quickAdd() {
        $objectManager = $this->em;
        
        $post = new \OmniBlog\Entity\Post();
        $post->__set('author', 'testUser');
        $post->__set('title', 'test');
        $post->__set('content', 'emTest');
        
        $objectManager->persist($post);
        $objectManager->flush();
        $data = array(
        		'posts' => $objectManager
        		->getRepository('OmniBlog\Entity\Post')
        		->findAll()
        );
        return new Viewmodel($data);
    }
    
    /**
     * The default action - show the home page
     */
    public function indexAction() {
        return new ViewModel(array(
        	'posts' => $this->getEntityManager()->getRepository('OmniBlog\Entity\Post')->findall()
        ));
    }

    public function addAction() {
        // Get your ObjectManager
        $objectManager = $this->getEntityManager();
        
        //Create the form and inject the ObjectManager
        $form = new PostForm($objectManager);

        $post = new Post();
        $form->bind($post);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        
        	if ($form->isValid()) {
        		$objectManager->persist($post);
        		$objectManager->flush();
        		return $this->redirect()->toRoute('omni-blog',
        				array('controller' => 'post')
        		);
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
        $entity = $objectManager->find('OmniBlog\Entity\Post', $id);
        $form->bind($entity);
        

        //Manage Associations here because couldn't find a way in fieldsets
        //Get Element 'categories'
        $element = $form->getBaseFieldset()->get('categories'); //Object of: DoctrineModule\\Form\\Element\\ObjectMultiCheckbox
        
        //Setup intial Associated_Categories
        $entity_links = $objectManager->getRepository('OmniBlog\Entity\CategoryPostAssociation')->findBy(array('post' => $entity));
        $startingIDs = array();
        foreach($entity_links as $link){
            $catId = $link->getCategory()->getId();
            array_push($startingIDs, $catId);
        }
        $element->setValue($startingIDs);
        
        //Submit Button Pressed
        if ($this->request->isPost()) {
        	$form->setData($this->request->getPost());
            
        	if ($form->isValid()) {
        	    //random test: $form->bindOnValidate();
        	    
        	    
        	    /* TODO: finish managing assocaitions
        	    //Manage Associations here because couldn't find a way in fieldsets
        	    $eValue = $element->getValue();
        	    //$eValue = Selected Associations
        	    //$startingIDs = old
        	    //$eValue = new
        	    
        	    foreach($entity_links as $link)
        	    {
        	        $catId = $link->getCategory()->getId();
        	        //array_push($idArray, $catId);
        	    }
        	    
        	    //Compare old to new.
        	    //Get what to remove
        	       //Whats missing from old in new
        	    //Get what to add
        	       //Whats missing from new in old
        	        
    	        */
        	    
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
    
    public function getModelTable()
    {
    	if (!$this->modelTable) {
    		$sm = $this->getServiceLocator();
    		$this->modelTable = $sm->get($this->tableRoute);
    	}
    	return $this->modelTable;
    }
}

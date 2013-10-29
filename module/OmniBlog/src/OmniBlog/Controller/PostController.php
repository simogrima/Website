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
        
        //Get Entity by ID & bind
        $entity = $this->getEntityManager()->find('OmniBlog\Entity\Post', $id);
        $form->bind($entity);
        
        $entity_links = $this->getEntityManager()->getRepository('OmniBlog\Entity\CategoryPostAssociation')->findBy(array('post' => $entity));
        //$objectManager->getRepository('OmniBlog\Form\CategoryPostAssociation')->findBy(array('post_id' => $this->po))
        //$this->getEntityManager()->find('OmniBlog\Entity\Post', $id);
//         $xmyPost = $form->getBaseFieldset();
//         $a = $xmyPost->getAttributes();
//         $o = $xmyPost->getOptions();
//         $v = $xmyPost->getValue();
        //$elements = $xmyPost->getElements();
        //$element = $xmyPost->get('categories');
        $element = $form->getBaseFieldset()->get('categories');
        $element->setValue($entity_links);
        //$element->setOptions($options)
        
        /*foreach($entity_links as $link){
            $element->setValue($link->getId());            
        }*/
        
//         if($element == \DoctrineModule\Form\Element\ObjectMultiCheckbox)
//         {
//             echo "bull shit";
//         }
        //categories	Object of: DoctrineModule\\Form\\Element\\ObjectMultiCheckbox
        
        if ($this->request->isPost()) {
        	$form->setData($this->request->getPost());
            
        	if ($form->isValid()) {
        	    //random test: $form->bindOnValidate();
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

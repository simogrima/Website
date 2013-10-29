<?php
namespace OmniBlog\Entity;

use Doctrine\ORM\Mapping as ORM;
/*
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
*/
/**
 * A post's comment or reply to comment.
 * @Orm\Entity
 * @ORM\Table(name="category")
 */
class Category //implements InputFilterAwareInterface 
{
    /**
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    protected $title;
    
    /*
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="categories")
     * Many to many breaks, so create a link table 
     * (http://codemonkeys.be/2013/02/example-of-a-doctrine-2-x-many-to-many-association-class/ )
    protected $posts;
    */
    
    /**
     * @ORM\OneToMany(targetEntity="CategoryPostAssociation", mappedBy="category")
     */
    protected $category_post_associations;
    
    
    protected $inputFilter;
    
    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    
    public function __construct() {
    	//$this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category_post_associations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __get($property)
    {
    	return $this->$property;
    }
    
    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value)
    {
    	$this->$property = $value;
    }
    
    public function getId(){
    	return $this->id;
    }
    protected function setId($value){
    	$this->id = $value;
    }
    
    public function getTitle(){
        return $this->title;
    }
    public function setTitle($value){
    	$this->title = $value;
    }
    
    /**
     * Add category_post_associations
     *
     * @param \OmniBlog\Entity\CategoryPostAssociation $categoryPostAssociations
     * @return Category
     */
    public function addCategoryPostAssociations(\OmniBlog\Entity\CategoryPostAssociation $categoryPostAssociations){
    	$this->category_post_associations[] = $categoryPostAssociations;
    
    	return $this;
    }
    
    /**
     * Remove category_post_associations
     *
     * @param \OmniBlog\Entity\CategoryPostAssociation $categoryPostAssociations
     */
    public function removeCategoryPostAssociations(\OmniBlog\Entity\CategoryPostAssociation $categoryPostAssociations){
    	$this->category_post_associations->removeElement($categoryPostAssociations);
    }
    
    /**
     * Get category_post_associations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategoryPostAssociations()
    {
    	return $this->category_post_associations;
    }
    
    /*
     * @param Collection
     public function addPosts(Collection $posts){
    	foreach ($posts as $post) {
    	    $post->setCategory($this);
    	    $this->posts->add($post);
    	}
    }
    
    public function removePosts(Collection $posts){
    	foreach ($posts as $post) {
    	    $post->setCategory(null);
    	    $this->posts->removeElement($post);
    	}
    }
    
    public function getPosts(){
    	return $this->posts;
    }
    */
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
    
    
    /**
     * Populate from an array.
     *
     * @param array $data
     */
    /*public function populate($data = array())
    {
    	$this->id = $data['id'];
    	$this->title = $data['title'];
    	$this->posts = $data['posts'];
    }
    */
    /*
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
    	throw new \Exception("Not used");
    }
    
    public function getInputFilter()
    {
    	if (!$this->inputFilter) {
    		$inputFilter = new InputFilter();
    
    		$factory = new InputFactory();
    
    		$inputFilter->add($factory->createInput(array(
    				'name'       => 'id',
    				'required'   => true,
    				'filters' => array(
    						array('name'    => 'Int'),
    				),
    		)));
    
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'author',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						array(
    								'name'    => 'StringLength',
    								'options' => array(
    										'encoding' => 'UTF-8',
    										'min'      => 1,
    										'max'      => 32,
    								),
    						),
    				),
    		)));
    
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'title',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						array(
    								'name'    => 'StringLength',
    								'options' => array(
    										'encoding' => 'UTF-8',
    										'min'      => 1,
    										'max'      => 32,
    								),
    						),
    				),
    		)));
    		
    		$inputFilter->add($factory->createInput(array(
    				'name'     => 'content',
    				'required' => true,
    				'filters'  => array(
    						array('name' => 'StripTags'),
    						array('name' => 'StringTrim'),
    				),
    				'validators' => array(
    						array(
    								'name'    => 'StringLength',
    								'options' => array(
    										'encoding' => 'UTF-8',
    										'min'      => 1,
    										'max'      => 1000,
    								),
    						),
    				),
    		)));
    
    		$this->inputFilter = $inputFilter;
    	}
    
    	return $this->inputFilter;
    }
    */
}
<?php
namespace OmniBlog\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A blog post.
 * @Orm\Entity
 * @ORM\Table(name="post")
 * @property string $content
 * @property string $author
 * @property string $title
 * @property int $id
 */
class Post implements InputFilterAwareInterface 
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
    
    /**
     * @ORM\Column(type="string", length=32, nullable=false)
     */
    protected $author;
    
    /**
     * @ORM\Column(type="datetime", name="posted_at")
     * */
    protected $postedAt;
    
    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     */
    protected $content;

    //* @JoinTable(name="Category_Post"
    /*
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="posts")
     * @ORM\JoinTable(name="posts_categories")
     *
    protected $categories;
    */
    /**
     * @ORM\OneToMany(targetEntity="OmniBlog\Entity\CategoryPostAssociation", mappedBy="post")
     */
    protected $category_post_associations;
    
    protected $inputFilter;
    
    public function __construct() {
    	//$this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->category_post_associations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
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
    public function getAuthor(){
    	return $this->author;
    }
    public function setAuthor($value){
    	$this->author = $value;
    }
    public function getPostedAt(){
    	if($this->postedAt == null){
    	    $this->postedAt = new \DateTime("now");
    	}
    	return $this->postedAt;
    }
//     public function setPostedAt($value = null){
//     	$this->postedAt = new \DateTime("now");
//     }
    public function getContent(){
    	return $this->content;
    }
    public function setContent($value){
    	$this->content = $value;
    }
    
    /**
     * Add category_post_associations
     *
     * @param \OmniBlog\Entity\CategoryPostAssociation $categoryPostAssociations
     * @return Post
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

    /*
    public function addCategories(Collection $categories){
    	foreach ($categories as $category) {
    		$category->addPost($this);
    		$this->categories->add($category);
    	}
    }
    
    public function removeCategories(Collection $posts){
    	foreach ($posts as $post) {
    		$post->removeCategory(null);
    		$this->posts->removeElement($post);
    	}
    }
    
    public function getPosts(){
    	return $this->posts;
    }
    */
    
    /**
     * Allow null to remove association
     */
    public function addCategory(Category $category){
        $this->categories->add($category);
    }
    public function removeCategory(Category $category){
    	$this->categories->removeElement($category);
    }
    public function getCategory()
    {
        return $this->category;
    }
//     public function getCategories(){
//     	return $this->categories;
//     }
//     public function setCategories($value){
//     	$this->categories = $value;
//     }
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
    public function populate($data = array())
    {
    	$this->id = $data['id'];
    	$this->author = $data['author'];
    	$this->title = $data['title'];
    	$this->content = $data['content'];
    	$this->categories = $data['categories'];
    	$this->postedAt = $data['postedAt'];
    }
    
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
}
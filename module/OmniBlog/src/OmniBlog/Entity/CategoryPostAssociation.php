<?php
namespace OmniBlog\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryPostAssociation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
// @ORM\Entity(repositoryClass="OmniBlog\Entity\CategoryPostAssociationRepository")
class CategoryPostAssociation
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 **/

	/**
	 *
	 * @ORM\ManyToOne(targetEntity="OmniBlog\Entity\Category", inversedBy="category_post_associations")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 *
	 */
	private $category;

	/**
	 *
	 * @ORM\ManyToOne(targetEntity="OmniBlog\Entity\Post", inversedBy="category_post_associations")
	 * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
	 */
	private $post;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set category
	 *
	 * @param \OmniBlog\Entity\Category $category
	 * @return CategoryPostAssociation
	 */
	public function setCategory(\OmniBlog\Entity\Category $category = null)
	{
		$this->category = $category;

		return $this;
	}

	/**
	 * Get category
	 *
	 * @return \OmniBlog\Entity\Category
	 */
	public function getCategory()
	{
		return $this->category;
	}

	/**
	 * Set post
	 *
	 * @param \OmniBlog\Entity\Post $post
	 * @return CategoryPostAssociation
	 */
	public function setPost(\OmniBlog\Entity\Post $post = null)
	{
		$this->post = $post;

		return $this;
	}

	/**
	 * Get post
	 *
	 * @return \OmniBlog\Entity\Post
	 */
	public function getPost()
	{
		return $this->post;
	}
}

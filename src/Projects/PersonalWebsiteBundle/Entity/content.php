<?php

namespace Projects\PersonalWebsiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * content
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Projects\PersonalWebsiteBundle\Entity\contentRepository")
 */
class content
{
	/**
	 * @ORM\ManyToOne(targetEntity="category", inversedBy="content")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;

	/**
	 * @ORM\ManyToOne(targetEntity="language", inversedBy="content")
	 * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
	 */
	protected $language;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var integer
     *
     * @ORM\Column(name="lang_id", type="integer")
     */
    private $langId;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer")
     */
    private $categoryId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


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
     * Set body
     *
     * @param string $body
     * @return content
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set langId
     *
     * @param integer $langId
     * @return content
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;

        return $this;
    }

    /**
     * Get langId
     *
     * @return integer 
     */
    public function getLangId()
    {
        return $this->langId;
    }


	/**
	 * Set CategoryId
	 *
	 * @param $categoryId
	 * @return content
	 */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get CategoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return content
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set category
     *
     * @param \Projects\PersonalWebsiteBundle\Entity\category $category
     * @return content
     */
    public function setCategory(\Projects\PersonalWebsiteBundle\Entity\category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Projects\PersonalWebsiteBundle\Entity\category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set language
     *
     * @param \Projects\PersonalWebsiteBundle\Entity\language $language
     * @return content
     */
    public function setLanguage(\Projects\PersonalWebsiteBundle\Entity\language $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Projects\PersonalWebsiteBundle\Entity\language 
     */
    public function getLanguage()
    {
        return $this->language;
    }
}

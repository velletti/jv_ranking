<?php
namespace JVE\JvRanking\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use JVelletti\JvEvents\Domain\Model\Category;
use JVelletti\JvEvents\Domain\Model\Tag;
/***
 *
 * This file is part of the "JV Ranking Module" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Amerigo Vellett <typo3@velletti.de>, none
 *
 ***/
/**
 * Question
 */
class Question extends AbstractEntity
{
    /**
     * question
     *
     * @var string
     * @Validate("NotEmpty")
     */
    protected $question = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * eventCategory
     *
     * @var ObjectStorage<Category>
     */
    protected $eventCategory = null;

    /**
     * tags
     *
     * @var ObjectStorage<Tag>
     */
    protected $tags = null;



    /**
     * Answer temporar Array
     *
     * @var array
     *
     */
    protected $answer = [] ;


    /**
 * value
 *
 * @var int
 */
    protected $value = 0;


    /**
     * value
     *
     * @var int
     */
    protected $tstamp ;

    /**
     * value
     *
     * @var int
     */
    protected $hidden = 0;

    /**
     * value
     *
     * @var int
     */
    protected $validUntil = 30 ;



    /**
     * this event is visible for the following usergroups / Access Rights
     *
     * @var string
     */
    protected $access = '';


    /**
     * value
     *
     * @var bool
     */
    protected $visible = false ;

    /**
     * Initializes all ObjectStorage properties
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->eventCategory = new ObjectStorage();
        $this->tags = new ObjectStorage();

    }
    /**
     * Returns the question
     *
     * @return string $question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question
     *
     * @param string $question
     * @return void
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * Returns the value
     *
     * @return int $value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value
     *
     * @param int $value
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns the description
     *
     * @return string description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param array $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }


    /**
     * @return int
     */
    public function getLocalizedUid()
    {
        return $this->_localizedUid;
    }

    /**
     * @param int $localizedUid
     */
    public function setLocalizedUid($localizedUid)
    {
        $this->_localizedUid = $localizedUid;
    }

    /**
     * @return int
     */
    public function getLanguageUid()
    {
        return $this->_languageUid;
    }

    /**
     * @param int $languageUid
     */
    public function setLanguageUid($languageUid)
    {
        $this->_languageUid = $languageUid;
    }


    /**
     * @return int
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @param int $hidden
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    /**
     * @return int
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * @param int $validUntil
     */
    public function setValidUntil($validUntil)
    {
        $this->validUntil = $validUntil;
    }

    /**
     * @return int
     */
    public function getVaidUntil()
    {
        return $this->vaidUntil;
    }

    /**
     * @param int $vaidUntil
     */
    public function setVaidUntil($vaidUntil)
    {
        $this->vaidUntil = $vaidUntil;
    }

    /**
     * @return int
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }



    /**
     * Adds a Category
     *
     * @param Category $eventCategory
     * @return void
     */
    public function addEventCategory(Category $eventCategory)
    {
        $this->eventCategory->attach($eventCategory);
    }

    /**
     * Removes a Category
     *
     * @param Category $eventCategoryToRemove The Category to be removed
     * @return void
     */
    public function removeEventCategory(Category $eventCategoryToRemove)
    {
        $this->eventCategory->detach($eventCategoryToRemove);
    }

    /**
     * Returns the eventCategory
     *
     * @return ObjectStorage<Category> $eventCategory
     */
    public function getEventCategory()
    {
        return $this->eventCategory;
    }

    /**
     * Sets the eventCategory
     *
     * @param ObjectStorage<Category> $eventCategory
     * @return void
     */
    public function setEventCategory(ObjectStorage $eventCategory)
    {
        $this->eventCategory = $eventCategory;
    }

    /**
     * Adds a Tag
     *
     * @param Tag $tag
     * @return void
     */
    public function addTag(Tag $tag)
    {
        $this->tags->attach($tag);
    }

    /**
     * Removes a Tag
     *
     * @param Tag $tagToRemove The Tag to be removed
     * @return void
     */
    public function removeTag(Tag $tagToRemove)
    {
        $this->tags->detach($tagToRemove);
    }

    /**
     * Returns the tags
     *
     * @return ObjectStorage<Tag> $tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Sets the tags
     *
     * @param ObjectStorage<Tag> $tags
     * @return void
     */
    public function setTags(ObjectStorage $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * @param string $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }



}

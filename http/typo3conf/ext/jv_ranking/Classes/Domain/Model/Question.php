<?php
namespace JVE\JvRanking\Domain\Model;

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
class Question extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * question
     *
     * @var string
     * @validate NotEmpty
     */
    protected $question = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

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



}

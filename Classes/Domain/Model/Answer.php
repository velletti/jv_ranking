<?php
namespace JVE\JvRanking\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation\Validate;
/***
 *
 * This file is part of the "JV Ranking Module" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Amerigo Velletti <typo3@velletti.de>, none
 *
 ***/
/**
 * Answer
 */
class Answer extends AbstractEntity
{
    /**
     * answer
     *
     * @var int
     * @Validate("NotEmpty")
     */
    protected $answer = 0;

    /**
     * @var int
     */
    protected  $starttime = 0 ;

    /**
     * organizerUid
     *
     * @var int
     */
    protected $organizerUid = 0;

    /**
     * question
     *
     * @var Question
     */
    protected $question = null;

    /**
     * Returns the answer
     *
     * @return int $answer
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Sets the answer
     *
     * @param int $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * Returns the question
     *
     * @return Question $question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question
     */
    public function setQuestion(Question $question)
    {
        $this->question = $question;
    }

    /**
     * Returns the organizerUid
     *
     * @return int $organizerUid
     */
    public function getOrganizerUid()
    {
        return $this->organizerUid;
    }

    /**
     * Sets the organizerUid
     *
     * @param int $organizerUid
     */
    public function setOrganizerUid($organizerUid)
    {
        $this->organizerUid = $organizerUid;
    }

    /**
     * @return int
     */
    public function getStarttime()
    {
        return $this->starttime;
    }

    /**
     * @param int $starttime
     */
    public function setStarttime($starttime)
    {
        $this->starttime = $starttime;
    }

}

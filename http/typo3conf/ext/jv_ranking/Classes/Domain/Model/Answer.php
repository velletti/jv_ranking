<?php
namespace JVE\JvRanking\Domain\Model;

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
class Answer extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * answer
     *
     * @var int
     * @validate NotEmpty
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
     * @var \JVE\JvRanking\Domain\Model\Question
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
     * @return void
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * Returns the question
     *
     * @return \JVE\JvRanking\Domain\Model\Question $question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question
     *
     * @param \JVE\JvRanking\Domain\Model\Question $question
     * @return void
     */
    public function setQuestion(\JVE\JvRanking\Domain\Model\Question $question)
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
     * @return void
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

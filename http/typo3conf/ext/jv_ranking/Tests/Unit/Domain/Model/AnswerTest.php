<?php
namespace JVE\JvRanking\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Amerigo Velletti <typo3@velletti.de>
 */
class AnswerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \JVE\JvRanking\Domain\Model\Answer
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \JVE\JvRanking\Domain\Model\Answer();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getAnswerReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getAnswer()
        );
    }

    /**
     * @test
     */
    public function setAnswerForIntSetsAnswer()
    {
        $this->subject->setAnswer(12);

        self::assertAttributeEquals(
            12,
            'answer',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getOrganizerUidReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getOrganizerUid()
        );
    }

    /**
     * @test
     */
    public function setOrganizerUidForIntSetsOrganizerUid()
    {
        $this->subject->setOrganizerUid(12);

        self::assertAttributeEquals(
            12,
            'organizerUid',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getQuestionReturnsInitialValueForQuestion()
    {
        self::assertEquals(
            null,
            $this->subject->getQuestion()
        );
    }

    /**
     * @test
     */
    public function setQuestionForQuestionSetsQuestion()
    {
        $questionFixture = new \JVE\JvRanking\Domain\Model\Question();
        $this->subject->setQuestion($questionFixture);

        self::assertAttributeEquals(
            $questionFixture,
            'question',
            $this->subject
        );
    }
}

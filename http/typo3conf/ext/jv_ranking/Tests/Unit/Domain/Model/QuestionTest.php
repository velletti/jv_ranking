<?php
namespace JVE\JvRanking\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Amerigo Velletti <typo3@velletti.de>
 */
class QuestionTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \JVE\JvRanking\Domain\Model\Question
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \JVE\JvRanking\Domain\Model\Question();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getQuestionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getQuestion()
        );
    }

    /**
     * @test
     */
    public function setQuestionForStringSetsQuestion()
    {
        $this->subject->setQuestion('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'question',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription()
    {
        $this->subject->setDescription('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'description',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getValueReturnsInitialValueForInt()
    {
        self::assertSame(
            0,
            $this->subject->getValue()
        );
    }

    /**
     * @test
     */
    public function setValueForIntSetsValue()
    {
        $this->subject->setValue(12);

        self::assertAttributeEquals(
            12,
            'value',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getAnswerReturnsInitialValueFor()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getAnswer()
        );
    }

    /**
     * @test
     */
    public function setAnswerForObjectStorageContainingSetsAnswer()
    {
        $answer = new ();
        $objectStorageHoldingExactlyOneAnswer = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneAnswer->attach($answer);
        $this->subject->setAnswer($objectStorageHoldingExactlyOneAnswer);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneAnswer,
            'answer',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addAnswerToObjectStorageHoldingAnswer()
    {
        $answer = new ();
        $answerObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $answerObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($answer));
        $this->inject($this->subject, 'answer', $answerObjectStorageMock);

        $this->subject->addAnswer($answer);
    }

    /**
     * @test
     */
    public function removeAnswerFromObjectStorageHoldingAnswer()
    {
        $answer = new ();
        $answerObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $answerObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($answer));
        $this->inject($this->subject, 'answer', $answerObjectStorageMock);

        $this->subject->removeAnswer($answer);
    }
}

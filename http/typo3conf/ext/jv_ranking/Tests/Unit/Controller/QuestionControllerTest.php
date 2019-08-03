<?php
namespace JVE\JvRanking\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Amerigo Velletti <typo3@velletti.de>
 */
class QuestionControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \JVE\JvRanking\Controller\QuestionController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\JVE\JvRanking\Controller\QuestionController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllQuestionsFromRepositoryAndAssignsThemToView()
    {

        $allQuestions = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $questionRepository = $this->getMockBuilder(\JVE\JvRanking\Domain\Repository\QuestionRepository::class)
            ->setMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $questionRepository->expects(self::once())->method('findAll')->will(self::returnValue($allQuestions));
        $this->inject($this->subject, 'questionRepository', $questionRepository);

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('questions', $allQuestions);
        $this->inject($this->subject, 'view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenQuestionToView()
    {
        $question = new \JVE\JvRanking\Domain\Model\Question();

        $view = $this->getMockBuilder(\TYPO3\CMS\Extbase\Mvc\View\ViewInterface::class)->getMock();
        $this->inject($this->subject, 'view', $view);
        $view->expects(self::once())->method('assign')->with('question', $question);

        $this->subject->showAction($question);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenQuestionFromQuestionRepository()
    {
        $question = new \JVE\JvRanking\Domain\Model\Question();

        $questionRepository = $this->getMockBuilder(\JVE\JvRanking\Domain\Repository\QuestionRepository::class)
            ->setMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $questionRepository->expects(self::once())->method('remove')->with($question);
        $this->inject($this->subject, 'questionRepository', $questionRepository);

        $this->subject->deleteAction($question);
    }
}

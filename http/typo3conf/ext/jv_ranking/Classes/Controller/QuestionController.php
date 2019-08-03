<?php
namespace JVE\JvRanking\Controller;

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
 * QuestionController
 */
class QuestionController extends \JVE\JvEvents\Controller\BaseController
{
    /**
     * questionRepository
     *
     * @var \JVE\JvRanking\Domain\Repository\QuestionRepository
     * @inject
     */
    protected $questionRepository = null;

    /**
     * answerRepository
     *
     * @var \JVE\JvRanking\Domain\Repository\AnswerRepository
     * @inject
     */
    protected $answerRepository = null;

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $organizer = $this->getOrganizer();

        $querysettings = new \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings ;
        // toDo set storage Pid here
        $querysettings->setStoragePageIds(array( 48 )) ;
        $this->questionRepository->setDefaultQuerySettings( $querysettings );


        $questions = $this->questionRepository->findAll()->toArray() ;
        $ansers = 0 ;
        /** @var \JVE\JvRanking\Domain\Model\Question $question */
        foreach ( $questions as $key => $question ) {
            /** @var \JVE\JvRanking\Domain\Model\Answer $answer */
            $answer = $this->answerRepository->getAnswerByOrganizerUid($question->getUid() , $organizer->getUid())->getFirst() ;
            if ( $answer) {
                $ansers ++ ;
                $arr = array( "answer" => $answer->getAnswer() ,  'date' => $answer->getStarttime() ) ;
                if( $answer->getStarttime() > mktime( 0 , 0 , 0 ,-1 , date("d") , date("Y"))) {
                    $arr['readOnly'] = 'readonly';
                }
                $question->setAnswer( $arr  ) ;
            } else {
                $question->setAnswer( array() ) ;
            }
        }
        $this->view->assign('ansers', $ansers);
        $this->view->assign('count', count( $questions));
        $this->view->assign('questions', $questions);
        $this->view->assign('organizer', $organizer);
    }

    /**
     * action show
     *
     * @param \JVE\JvRanking\Domain\Model\Question $question
     * @return void
     */
    public function showAction(\JVE\JvRanking\Domain\Model\Question $question)
    {
        $this->view->assign('question', $question);
    }

    /**
     * action delete
     *
     * @param \JVE\JvRanking\Domain\Model\Question $question
     * @return void
     */
    public function deleteAction(\JVE\JvRanking\Domain\Model\Question $question)
    {
        $this->addFlashMessage('The object was NOT  deleted. This action is actually not needed ', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->redirect('list');
    }

    /**
     * action save
     *
     * @return void
     */
    public function saveAction()
    {
        $request = $this->request->getArguments() ;
        if ( $this->request->hasArgument("questions") ) {
            $questions = $this->request->getArgument("questions") ;
        }
        echo "<pre>" ;
        var_dump($questions) ;


        $organizer = $this->getOrganizer();

        $querysettings = new \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings ;
        // toDo set storage Pid here
        $querysettings->setStoragePageIds(array( 48 )) ;

        $this->questionRepository->setDefaultQuerySettings( $querysettings );
        $this->answerRepository->setDefaultQuerySettings( $querysettings );

        $totalValue = 0 ;
        $answers = $this->answerRepository->findAll()->toArray() ;
        /** @var \JVE\JvRanking\Domain\Model\Answer $answer */
        foreach ( $answers as $key => $answer ) {
            $answerIsUpdated = false ;
            if( $answer->getStarttime() < mktime( 0 , 0 , 0 ,-1 , date("d") , date("Y"))) {
                // answer is older than 30 days and may be changed
                if( is_array($questions )) {
                    foreach ( $questions as $id =>  $value) {
                        // check if answer is in array of new answer
                        if( $answer->getQuestion()->getUid() == $id ) {
                            $totalValue = $totalValue + $answer->getQuestion()->getValue() ;
                            $answer->setStarttime( time() ) ;
                            $answerIsUpdated = true ;
                            unset( $questions[$id] ) ;
                        }
                    }
                }
                // answer does not exist anymore in response
                if( !$answerIsUpdated ) {
                    $this->answerRepository->remove($answer) ;
                }
            } else {
                // answer may not be changed as it was set in less than 30 days
                if( is_array($questions )) {
                    foreach ( $questions as $id =>  $value) {

                        if( is_object($answer->getQuestion() ) && $answer->getQuestion()->getUid() == $id ) {
                            $totalValue = $totalValue + $answer->getQuestion()->getValue() ;
                            unset( $questions[$id] ) ;
                        }
                    }
                }
            }

        }
        if( is_array($questions )) {
            foreach ( $questions as $id =>  $value) {
                /** @var \JVE\JvRanking\Domain\Model\Answer $newAnswer */
                $newAnswer = $this->objectManager->get( "JVE\\JvRanking\\Domain\\Model\\Answer")  ;
                $question = $this->questionRepository->findOneByUid($id ) ;
                if( is_object( $question )) {
                    $totalValue = $totalValue + $question->getValue() ;
                    echo "<br>id/ vlaue: " . $id . "/" . $value ;
                    $newAnswer->setPid(48) ;
                    $newAnswer->setOrganizerUid($organizer->getUid()) ;
                    $newAnswer->setQuestion($question) ;
                    $newAnswer->setAnswer( 1 ) ;
                    $newAnswer->setStarttime( time() ) ;

                    $this->answerRepository->add($newAnswer) ;
                }



                unset($newAnswer) ;
            }
        }

        // Todo  $totalValue korrekt berechnen
         $organizer->setSorting($totalValue) ;
        // ToDo Free / Silver / Gold neu berechnen .. und Categorien setzen/korrgieren

         $this->organizerRepository->update( $organizer) ;



        $this->persistenceManager->persistAll();
        $this->addFlashMessage("Ranking settings updated! " , "Success" , \TYPO3\CMS\Core\Messaging\AbstractMessage::OK) ;
        $this->redirect('list');
    }
}

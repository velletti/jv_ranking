<?php
namespace JVE\JvRanking\Controller;

use JVelletti\JvEvents\Controller\BaseController;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use JVelletti\JvEvents\Domain\Model\Category;
use JVelletti\JvEvents\Domain\Model\Organizer;
use JVelletti\JvEvents\Domain\Model\Tag;
use JVE\JvRanking\Domain\Model\Answer;
use JVE\JvRanking\Domain\Model\Question;
use JVE\JvRanking\Domain\Repository\AnswerRepository;
use JVE\JvRanking\Domain\Repository\QuestionRepository;
use JVE\JvRanking\Utility\RankingUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

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
class QuestionController extends BaseController
{
    const storagePid = 52 ;
    /**
     * questionRepository
     *
     * @var QuestionRepository
     */
    protected $questionRepository = null;

    /**
     * answerRepository
     *
     * @var AnswerRepository
     */
    protected $answerRepository = null;


    /**
     * action initialize
     *
     * @return void
     */
    public function initializeAction()
    {
        $this->questionRepository = GeneralUtility::makeInstance(QuestionRepository::class) ;
        $this->answerRepository = GeneralUtility::makeInstance(AnswerRepository::class) ;
    }

    public function injectAnswerRepository(AnswerRepository $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    public function injectQuestionRepository(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * action list
     *
     * @return void
     * @throws NoSuchArgumentException
     * @throws InvalidQueryException
     */
    public function listAction(): ResponseInterface
    {
        $answers = null;
        $organizer = $this->getOrganizer();
        $filter = [];
        if ($organizer) {
            /** @var Typo3QuerySettings $querysettings */
            $querysettings = $this->questionRepository->getTYPO3QuerySettings();
            // toDo set storage Pid here
            $querysettings->setStoragePageIds([$this->getStoragePid()]);
            $this->answerRepository->setDefaultQuerySettings($querysettings);
    
            $querysettings->setIgnoreEnableFields(TRUE);
            $this->questionRepository->setDefaultQuerySettings($querysettings);
    
    
            $questions = $this->questionRepository->findAll()->toArray();
            $ansers = 0;
            $changeableAnswers = 0;
            /** @var Question $question */
            foreach ($questions as $key => $question) {
                $debug = '';
                $needToCountEvents = false;
                $notEnoughEvents = false;
                $filter = [];
                $filter['organizer'] = $organizer->getUid();
                $filter['startDate'] = -100;
                $filter['maxDays'] = 365;
    
                $tags = $question->getTags();
                if ($tags && count($tags) > 0) {
                    $tags = $tags->getArray();
                    if (is_array($tags)) {
                        /** @var Tag $tag */
                        foreach ($tags as $tag) {
                            $filter['tags'] .= $tag->getUid() . ",";
                        }
                        $needToCountEvents = true;
                    }
                }
                /** @var Category $category */
                foreach ($question->getEventCategory() as $category) {
                    if (is_object($category)) {
                        $filter['categories'] .= $category->getUid() . ",";
                    }
                    $needToCountEvents = true;
                }
                if ($needToCountEvents) {
    
                    $events = $this->eventRepository->findByFilter($filter);
                    $debug = 'needToCountEvents ' . $needToCountEvents
                        . ' filter= ' . var_export($filter, true)
                        . ' - Event Count: : ' . (is_countable($events) ? count($events) : 0);
                    if ((is_countable($events) ? count($events) : 0) < 1) {
                        $notEnoughEvents = true;
                        $debug .= " notEnoughEvents: " . $notEnoughEvents;
                    }
    
                }
    
                /** @var Answer $answer */
                $answer = $this->answerRepository->getAnswerByOrganizerUid($question->getUid(), $organizer->getUid())->getFirst();
                if ($answer) {
                    $debug .= " | current Answer: " . $answer->getAnswer();
                    $answers++;
                    $arr = ['answer' => $answer, 'date' => $answer->getStarttime()];
                    if ($notEnoughEvents && ($answer->getStarttime() > time() || $question->getHidden())) {
                        unset($arr['answer']);
                        $debug .= " |  remove answer ";
                    }
    
    
                    if ($question->getHidden() || $notEnoughEvents) {
    
                        $debug .= " | set answer to readonly ";
                        $arr['readOnly'] = 'readonly';
                    } else {
                        if ($answer->getStarttime() > time() && $answer->getAnswer()) {
                            $arr['readOnly'] = 'readonly';
                            $debug .= " | answer was YES so readonly ";
                        } else {
                            $changeableAnswers++;
                        }
    
    
                    }
    
    
                } else {
                    $debug .= " | NO current Answer ";
                    $arr = [];
                    if ($question->getHidden() || $notEnoughEvents) {
                        $arr['readOnly'] = 'readonly';
                        $debug .= " | empty answer is readonly ";
                    } else {
                        $changeableAnswers++;
                    }
    
                }
                if ($question->getAccess()) {
                    if (!$this->hasUserGroup($question->getAccess())) {
                        $debug .= " User has no access ";
                        if ($question->isVisible()) {
                            unset($arr['answer']);
                            unset($arr['date']);
                            $arr['readOnly'] = 'readonly';
                            $changeableAnswers--;
                            $debug .= " | but Question is visible ";
                        } else {
                            unset($questions[$key]);
                            $changeableAnswers--;
                        }
                    }
                }
                $arr['debug'] = $debug;
                $question->setAnswer($arr);
            }
        }
        $this->view->assign('answers', $answers);
        $this->view->assign('storagePID', $this->getStoragePid());
        $this->view->assign('changeableAnswers', $changeableAnswers);
        $this->view->assign('count', (is_countable($questions) ? count( $questions) : 0 ));
        $this->view->assign('questions', $questions);
        $this->view->assign('organizer', $organizer);
        $this->view->assign('user',  $this->getUser() );
        return $this->htmlResponse();


    }

    /**
     * action show
     *
     * @return void
     */
    public function showAction(Question $question): ResponseInterface
    {
        $this->view->assign('question', $question);
        return $this->htmlResponse();
    }

    /**
     * action delete
     *
     * @return void
     * @throws StopActionException
     * @throws UnsupportedRequestTypeException
     */
    public function deleteAction(Question $question)
    {
        $this->addFlashMessage('The object was NOT  deleted. This action is actually not needed ', '', AbstractMessage::WARNING);
        return $this->redirect('list');
    }

    /**
     * action save
     *
     * @return void
     * @throws InvalidQueryException
     * @throws NoSuchArgumentException
     * @throws StopActionException
     * @throws UnsupportedRequestTypeException
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function saveAction()
    {
        $organizer = null;
        $questions = [];
        if ( $this->request->hasArgument("questions") ) {
            $questions = $this->request->getArgument("questions") ;
        }
        /** @var Organizer $organizer */
        try {
            $organizer = $this->getOrganizer();
        } catch (NoSuchArgumentException|InvalidQueryException) {
        }


        $debug = "\n ***************************************************" . "\n" ."Organizer: " . $organizer->getUid() . " - " . $organizer->getEmail()
            . " Old Sorting: " . $organizer->getSorting() ;
        $debug .= "\n ***************************************************" ;

        $answerCount = 0 ;
        if( is_array($questions )) {
            $answerCount = count($questions);
            $debug .= "\n Total Answers: " . $answerCount ;
            $debug .= "\n submitted request questions : " . var_export($questions , true ) ;
            $debug .= "\n ***************************************************" ;
        }


        /** @var Typo3QuerySettings $querysettings */
        $querysettings = $this->questionRepository->getTYPO3QuerySettings() ;

        // toDo set storage Pid here from TypoScript or something else ..
        $querysettings->setStoragePageIds([$this->getStoragePid()]) ;
        $this->questionRepository->setDefaultQuerySettings( $querysettings );

        $querysettings->setIgnoreEnableFields(TRUE) ;
        $this->answerRepository->setDefaultQuerySettings( $querysettings );

        $totalValue = 0 ;
        $answers = $this->answerRepository->getAllAnswersByOrganizerUid( $organizer->getUid() )->toArray() ;
        if( !is_array($answers)) {
            $debug .= "\n No Previous answers found. First Time using ranking Module" ;
            $debug .= "\n ***************************************************" ;
        } else {
            $debug .= "\n found Answers: " . count( $answers) ;
            $debug .= "\n ***************************************************" ;
            /** @var Answer $answer */
            foreach ( $answers as $key => $answer ) {
                $debug .= "\n Checking old Answer: " . $answer->getUid()  ;
                $answerIsUpdated = false ;
                $answQuestion = $answer->getQuestion() ;
                if( $answer->getStarttime() < time() ) {
                    // answer is older than valid_unitl Days days and may be changed
                    if( is_array($questions ) && $answQuestion ) {
                        foreach ( $questions as $id =>  $value) {
                            // check if answer is in array of new answer
                            if( $answQuestion->getUid() == $id ) {
                                $debug .= "\n Answer Updated: " . $answQuestion->getQuestion() ;
                                $totalValue = $totalValue + $answQuestion->getValue()  . " val: " . $answer->getQuestion()->getValue()  ;
                                $answer->setStarttime( time() + ( $answQuestion->getValidUntil() *3600 * 24 ) ) ;
                                $debug .= "\n Answer valid until: " . date( "d.m.Y H:i" , $answer->getStarttime()) ;

                                $answerIsUpdated = true ;
                                $this->answerRepository->update( $answer ) ;
                                unset( $questions[$id] ) ;
                            }
                        }
                    }
                    // answer does not exist anymore in response
                    if( !$answerIsUpdated ) {
                        if( $answer->getQuestion() ) {
                            $debug .= "\n Answer removed: " . $answer->getQuestion()->getQuestion() ;
                        }
                        $this->answerRepository->remove($answer) ;
                    }
                } else {
                    // answer may not be changed as it was set in less than 30 days
                    if( is_array($questions )) {
                        foreach ( $questions as $id =>  $value) {

                            if( is_object($answer->getQuestion() ) && $answer->getQuestion()->getUid() == $id ) {
                                $debug .= "\n Answer unchanged: " . $answer->getQuestion()->getQuestion() . " val: " . $answer->getQuestion()->getValue()  ;
                                $totalValue = $totalValue + $answer->getQuestion()->getValue() ;
                                unset( $questions[$id] ) ;
                            }
                        }
                    }
                }


            }
        }

        if( is_array($questions )) {
            foreach ( $questions as $id =>  $value) {

                $debug .= "\n Now adding new answers: " ;
                $debug .= "\n ***************************************************" ;
                /** @var Answer $newAnswer */
                $newAnswer = GeneralUtility::makeInstance( Answer::class)  ;
                /** @var Question $questionObj */
                $questionObj = $this->questionRepository->findByUid($id ) ;
                if( is_object( $questionObj )) {
                    $debug .= "\n Answer Added: " . $questionObj->getQuestion() ;
                    $totalValue = $totalValue + $questionObj->getValue()  . " val: " . $questionObj->getValue()  ;

                    $newAnswer->setPid($this->getStoragePid() ) ;
                    $newAnswer->setOrganizerUid($organizer->getUid()) ;
                    $newAnswer->setQuestion($questionObj) ;
                    $newAnswer->setAnswer( 1 ) ;
                    $newAnswer->setStarttime( time() + ( $questionObj->getValidUntil() *3600 * 24 ) ) ;

                    $this->answerRepository->add($newAnswer) ;
                } else {
                    $debug .= "\n Error: could not adding new answer: Question Uid " . $id . " not Found !";
                    $debug .= "\n ***************************************************" ;
                }



                unset($newAnswer) ;
            }
        }
        $this->persistenceManager->persistAll() ;

        $debug .= "\n ***************************************************" ;
        $isVip = $this->hasUserGroup( 3 ) ;
        $lastLogin =  $GLOBALS['TSFE']->fe_user->user['lastlogin'] ;
        if( $GLOBALS['TSFE']->fe_user->user['is_online'] > $lastLogin ) {
            $lastLogin =  $GLOBALS['TSFE']->fe_user->user['is_online'] ;
        }

        $result = RankingUtility::calculate($this->questionRepository, $organizer , $this->eventRepository , $this->answerRepository , $isVip , $lastLogin) ;
        $newSorting = $result['newsorting'] ;
        $debug .= $result['debug'] ;
        $hasGroup = $result['hasGroup'] ;
        $categories = $result['categories'] ;



        /* +++++++++++++    SET the New Group +++++++++++++++++++++++++++++++++++ */

        if ( $newSorting < 7000 ) {
            $newGroup = 11 ;
            $debug .= "\n" . "NewGroup ID  : " . $newGroup . " Platin" ;

        } else  if ( $newSorting < 7700 ) {
            $newGroup = 9 ;
            $debug .= "\n" . "NewGroup ID  : " . $newGroup . " Gold" ;

        } else  if ( $newSorting < 9400 ) {
            $newGroup = 8 ;
            $debug .= "\n" . "NewGroup ID  : " . $newGroup . " Silver" ;

        } else {
            $newGroup = 7 ; // free
            $debug .= "\n" . "NewGroup ID  : " . $newGroup . " Free" ;
        }
        $newGroupInfo = '';
        if( $hasGroup[$newGroup]) {
            $debug .= "\n" . "User has Group, do nothing .." ;
        } else {
            $debug .= "\n" . "User needs New  Group " ;
            foreach ( $categories as $category ) {
                if( $category->getUid() > 6 && $category->getUid() < 12 && $category->getUid() != $newGroup ) {
                    $organizer->getOrganizerCategory()->detach( $category) ;
                    $debug .= "\n" . "Removed  Category Group: " .  $category->getUid() . " - " . $category->getTitle()  ;
                }
            }
            $category = $this->categoryRepository->findByUid($newGroup) ;
            if( is_object($category)) {
                $organizer->getOrganizerCategory()->attach($category) ;
                $debug .= "\n" . "Added  Category Group: " .  $category->getUid() . " - " . $category->getTitle()  ;
                $newGroupInfo = " | Typ: " . $category->getTitle() ;
            }

        }

        /* +++++++++++++    SET the New Group +++++++++++++++++++++++++++++++++++ */


        $oldSorting =  $organizer->getSorting( ) ;
        $debug .= "\n" . "OldSorting  was : " . $oldSorting ;
        $posOld = $this->organizerRepository->findBySortingAllpages($oldSorting)->count() ;
        $debug .= "\n" . "Old Position was : " . $posOld ;

        $organizer->setSorting( $newSorting) ;

         $this->organizerRepository->update( $organizer) ;


        $this->persistenceManager->persistAll();

        $debug .= "\n" . "Count with NewSorting : " . $newSorting ;
        $posNew = $this->organizerRepository->findBySortingAllpages($newSorting)->count() ;
        $debug .= "\n" . "New Position is : " . $posNew ;

        $this->sendDebugEmail('tango@velletti.de','info@tangomuenchen.de' ,'[Ranking] ' . $organizer->getUid() . " - " . $organizer->getEmail() , $debug ) ;

        $this->addFlashMessage("Ranking settings updated! Deine neue Position in der Veranstalterliste ist in spätestens 24 Stunden aktiv." , "Success" , AbstractMessage::OK) ;
        $this->addFlashMessage("Bisherige Position: ". $posOld . " Neue Position: " . $posNew . " " . $newGroupInfo  , "" , AbstractMessage::NOTICE) ;

        return $this->redirect('list' , null , null, ["organizer" => $organizer->getUid()]);
    }
    public function getStoragePid() {
        return isset($this->settings['storagePid']) ? intval($this->settings['storagePid']) : $this::storagePid ;
    }
    
    public function getUser() {
        return ( $this->request->getAttribute('frontend.user')->user ) ?? null ;
    }

}

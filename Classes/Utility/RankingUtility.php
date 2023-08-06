<?php


namespace JVE\JvRanking\Utility;


use JVE\JvEvents\Domain\Model\Category;
use JVE\JvEvents\Domain\Model\Organizer;
use JVE\JvEvents\Domain\Repository\EventRepository;
use JVE\JvRanking\Domain\Model\Answer;
use JVE\JvRanking\Domain\Model\Question;
use JVE\JvRanking\Domain\Repository\AnswerRepository;
use JVE\JvRanking\Domain\Repository\QuestionRepository;

class RankingUtility
{

    /**
     * @param QuestionRepository $questionRepository
     * @param Organizer $organizer
     * @param EventRepository $eventRepository
     * @param AnswerRepository $answerRepository
     * @param boolean $isVip
     * @param integer $lastLogin
     * @return array
     */
    static public function calculate($questionRepository, $organizer , $eventRepository , $answerRepository , $isVip , $lastLogin )
    {
        $debug = "\n calculate points for organizer .... : " . $organizer->getUid()  ;
        $answers = $answerRepository->getAllAnswersByOrganizerUid( $organizer->getUid() )->toArray() ;
        $totalValue= 0 ;
        $answerCount= 0 ;
        if( is_array($answers)) {
            /** @var Answer $answer */
            foreach ( $answers as $key => $answer ) {

                $quest = $answer->getQuestion() ;
                if( $quest && $answer->getStarttime() > time() ) {
                    $answerCount ++ ;
                    $totalValue = $totalValue + $quest->getValue() ;
                    $debug .= "\n TotalValue: "  .  $quest->getValue() . " / " . $totalValue . " = " . $quest->getQuestion()   ;
                }
            }
        }
        $allQuestions = $questionRepository->getAllPages()->toArray() ;
        $allAnswers= 0 ;
        $allAnswersPoint = 0 ;
        $allHiddenAnswers = 0 ;
        /** @var Question $singleQuestion */
        foreach ( $allQuestions as $key => $singleQuestion ) {
            if( $singleQuestion->getLanguageUid() == 0 ) {
                $allAnswersPoint = $allAnswersPoint + $singleQuestion->getValue() ;
                if( $singleQuestion->gethidden()) {
                    $allHiddenAnswers ++ ;
                }
                $allAnswers ++ ;
            }

        }
        $debug .= "\n ***************************************************" ;
        $debug .= "\n" . "Possible Points: " . $allAnswersPoint ;
        $debug .= "\n" . "Possible Answers: " . $allAnswers ;
        $debug .= "\n" . "possible Bonus: " . ($allAnswers * $allAnswers * 10 ) ;
        $debug .= "\n" . "Hidden Answers: " . $allHiddenAnswers ;
        $debug .= "\n ***************************************************" ;

        // OLD: wir starten bei 7988 / 9679
        // 25.6.2022 :  statt  5015 bei neuen Veranstaltern bzw. alten  510 .
        // meu
        // = (2022 - 1960) * 333 - ( 6 * 3 )  = 62 * 333 - 18 = 9282
        // = (1999 - 1960) * 333 - ( 9 * 3 )  = 41 * 333 - 27 = 4055

        $Basemax =  ((( date( "Y" )  - 1960 ) * 150 )  - ( 3 *  date( "m" )) ) ;
        $debug .= "\n" . "Base Value (maximum): " . $Basemax ;
        $crYear = date( "Y" , $organizer->getCrdate() ) ;
        $crMonth = date( "m" , $organizer->getCrdate() ) ;

        $debug .= "\n" . "crYear: " . $crYear . " | Month: " . $crMonth ;
        $debug .= "\n" . "crYear: " . $crYear . " - 1960 = " . ( $crYear - 1960 ) . " * 150 = " .   ( $crYear - 1960 ) * 150 . " - " . ( $crMonth  * 3 );


        $base = (( $crYear - 1960 )  * 150 ) - ( $crMonth  * 3 ) ;

        $debug .= "\n" . "New Base: " . $base . " | got: " . ($Basemax - $base ) . " for long existing Organizer";
        $debug .= "\n" . "Total Bonus Value from Ranking: " . $totalValue . " / " . ( $allAnswersPoint + ($allAnswers * $allAnswers * 10) );



        // dann ziehen wir die Antworten ab und , je mehr anworten, um so höher der Bonus
        $newSorting = intval(  $base - $totalValue ) ;
        $debug .= "\n" . "NewSorting: " . $newSorting ;
        $debug .= "\n" . "AnswerCount: " . $answerCount  . " = $answerCount * $answerCount * 10 = (" . ( $answerCount * $answerCount * 10 )  . ") / max: " . ( $allAnswers * $allAnswers * 10 );
        $newSorting = $newSorting - ( $answerCount * $answerCount * 10 )  ;
        $debug .= "\n" . "NewSorting: " . $newSorting ;
        $debug .= "\n ***************************************************" ;
        $debug .= "\n Now calculation Bonus for all events, Dance Events, Live Music and more" ;
        $danceBonusMax = 0 ;
        $danceBonus = 0 ;
        // hat der User die manuelle Gruppe VIP ? macht 100 Punkte Bonus
        // if( $this->hasUserGroup( 3 )) {
        $danceBonusMax += 100 ;
        if( $isVip ) {
            $newSorting = $newSorting - 100 ;
            $debug .= "\n" . "user Is VIP (100 Bonus) : " . $newSorting ;

            $danceBonus += 100 ;
        }
        $filter['organizer'] = $organizer->getUid() ;
        $filter['startDate'] = -50 ;
        $filter['maxDays'] = 365 ;

        // Veranstalter mit vielen Events bekommen noch mal einen Bonus
        $events = $eventRepository->findByFilter($filter ) ;
        $eventCount = count( $events) ;
        $danceBonusMax += 800 ;
        $debug .= "\n" . "Event Count: (max 200 aber 4 fach )  : " .  $eventCount  ;
        $newSorting = $newSorting - ( min( $eventCount , 200 ) * 4 ) ;
        $danceBonus += ( min( $eventCount , 200 ) * 4 ) ;
        $debug .= "\n" . "NewSorting: " . $newSorting ;

        // tanz events noch mal bewerten
        $filter['categories'] = "1," ;
        $events = $eventRepository->findByFilter($filter ) ;
        $eventCount = count( $events) ;
        $debug .= "\n" . "Dance Event Count: (max 50 aber 10 fach) : " .  $eventCount  ;
        $newSorting = $newSorting - ( min( $eventCount , 50 ) * 10 )  ;

        $danceBonusMax +=  500 ;
        $danceBonus +=  ( min( $eventCount , 50 ) * 10 )  ;
        $debug .= "\n" . "NewSorting: " . $newSorting ;



        // live Musik: muss belohnt werden
        $filter['tags'] = "4," ;
        $events = $eventRepository->findByFilter($filter ) ;
        $eventCount = count( $events) ;
        $debug .= "\n" . "LIVE Musik  Event Count: (max 5 aber 100 fach ): " .  $eventCount  ;
        $newSorting = $newSorting - ( min( $eventCount , 5) * 100 )  ;
        $danceBonusMax +=  500 ;
        $danceBonus +=  ( min( $eventCount , 5 ) * 100 )   ;
        $debug .= "\n" . "NewSorting: " . $newSorting ;

        // Show : muss auch etwas belohnt werden
        $filter['tags'] = "11," ;
        $events = $eventRepository->findByFilter($filter ) ;
        $eventCount = count( $events) ;
        $danceBonusMax +=  50 ;
        $danceBonus +=  ( min( $eventCount , 10 ) * 5 )    ;
        $debug .= "\n" . "SHOW Events Count: (max 10 aber 5 fach ): " .  $eventCount  ;
        $newSorting = $newSorting - ( min( $eventCount , 10 ) * 5 )  ;
        $debug .= "\n" . "NewSorting: " . $newSorting ;

        $categories = $organizer->getOrganizerCategory()->getArray() ;
        $hasGroup = [] ;
        $hasGroup[7] = false ;
        $hasGroup[8] = false ;
        $hasGroup[9] = false ;
        $hasGroup[11] = false ;

        $debug .= "\n ***************************************************" ;
        $debug .= "\n" . "Possible Events Bonus Max: " . $danceBonusMax ;
        $debug .= "\n" . "Got Events Bonus : " . $danceBonus ;
        $debug .= "\n ***************************************************" ;

        if( is_object($organizer->getTeaserImage()) && $organizer->getTeaserImage()->getOriginalResource()) {
            $debug .= "\n" . "Organizer has Picture : additional 50 points "     ;
            $newSorting = $newSorting -  50  ;
            $debug .= "\n" . "NewSorting: " . $newSorting ;
        }

        /** @var Category $category */
        foreach ( $categories as $category ) {

            if( $category->getUid() > 6 && $category->getUid() < 12 ) {
                $hasGroup[ $category->getUid() ] = true ;
            }
            $newSorting = $newSorting - ( $category->getUid() *3 )  ;
            $debug .= "\n" . "Has Category: " .  $category->getUid() . " - " . $category->getTitle() . " Bonus: " .  ( $category->getUid() *3 )  ;
        }


        $random = ( date("s") * 2 ) ;
        $debug .= "\n" . " Before Random : " . $newSorting . " + random  :  " . $random;
        $newSorting = $newSorting - $random;
        $debug .= "\n" . "NewSorting after random: " . $newSorting ;
        $debug .= "\n ***************************************************" ;
        if( $newSorting < 100) {
            // dann random...
            $newSorting = 40 + date("s") ;
            $debug .= "\n" . "NewSorting as was < 100 : " . $newSorting ;
        }


        // pro tag nicht eingeloggt = 24 Punkte wieder oben drauf ..
        $debug .= "\n" . " LastLogin: " . date("d.m.Y" , $lastLogin )  ;
        if( $lastLogin > -1 ) {
            $lastLoginMalus = intval(  (( time() - $lastLogin ) / (3600) )) ;
            $newSorting = intval( $newSorting +  $lastLoginMalus  );
            $debug .= "\n" . "NewSorting after LastLogin: " . date("d.m.Y" , $lastLogin ) . " -> got malus: " .  $lastLoginMalus . " => ". $newSorting ;
        }
        /* +++++++++++++    SET New Sorting for J Velletti always to 10  +++++++++++++++++++++++++++++++++++ */
        if( strtolower( $organizer->getEmail()) == "joergvelletti@gmx.de") {
            $newSorting = 10  ;
        }
        return ["newsorting" => $newSorting , "debug" => $debug , "hasGroup" => $hasGroup , "categories" => $categories  ] ;

    }
}
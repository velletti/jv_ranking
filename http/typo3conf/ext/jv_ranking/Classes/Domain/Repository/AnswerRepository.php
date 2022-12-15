<?php
namespace JVE\JvRanking\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser;
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
 * The repository for Answers
 */
class AnswerRepository extends Repository
{

    public function getAnswerByOrganizerUid( $questionUid , $organizerUid )
    {
        $query = $this->createQuery();
        $querySettings = $query->getQuerySettings() ;
        $querySettings->setRespectStoragePage(false);
        $querySettings->setRespectSysLanguage(FALSE);
        $query->setQuerySettings($querySettings) ;

        $query->setLimit(1) ;
        $constraints[] = $query->equals('organizerUid', $organizerUid ) ;
        $constraints[] = $query->equals('question', $questionUid ) ;
        $query->matching($query->logicalAnd($constraints));
        $res = $query->execute() ;

        // new way to debug typo3 db queries
        // $queryParser = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser::class);
        // var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL());
        // var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters()) ;
        // die;
        return $res ;
    }

    public function getAllAnswersByOrganizerUid(  $organizerUid )
    {
        $query = $this->createQuery();
        $querySettings = $query->getQuerySettings() ;
        $querySettings->setRespectStoragePage(false);
        $querySettings->setRespectSysLanguage(FALSE);
        $querySettings->setIgnoreEnableFields(TRUE);
        $query->setQuerySettings($querySettings) ;

        $constraints[] = $query->equals('organizerUid', $organizerUid ) ;
        $constraints[] = $query->equals('hidden', 0 ) ;
        $constraints[] = $query->equals('deleted', 0 ) ;
        $query->matching($query->logicalAnd($constraints));

        $res = $query->execute() ;

        // new way to debug typo3 db queries
        if( $organizerUid == 485 && 1==2  ) {
            $queryParser = $this->objectManager->get(Typo3DbQueryParser::class);
            var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getSQL());
            var_dump($queryParser->convertQueryToDoctrineQueryBuilder($query)->getParameters());
            die;
        }
        return $res ;
    }
}

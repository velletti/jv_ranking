<?php
defined('TYPO3') || die('Access denied.' );

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'JvRanking',
            'Pi1',
            'Organizer Ranking'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('jv_ranking', 'Configuration/TypoScript', 'JV Ranking Module');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jvranking_domain_model_question', 'EXT:jv_ranking/Resources/Private/Language/locallang_csh_tx_jvranking_domain_model_question.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jvranking_domain_model_question');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_jvranking_domain_model_answer', 'EXT:jv_ranking/Resources/Private/Language/locallang_csh_tx_jvranking_domain_model_answer.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_jvranking_domain_model_answer');

    }
);
## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
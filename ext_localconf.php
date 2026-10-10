<?php
defined('TYPO3') || die('Access denied.' );

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'JvRanking',
            'Pi1',
            [
                \JVE\JvRanking\Controller\QuestionController::class => 'list, show, save, delete'
            ],
            // non-cacheable actions
            [
                \JVE\JvRanking\Controller\QuestionController::class => 'list, show, save, delete'
            ],
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

    }
);
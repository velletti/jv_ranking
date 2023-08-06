<?php
defined('TYPO3') || die('Access denied.');

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
            ]
        );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    pi1 {
                        iconIdentifier = jv_ranking-plugin-pi1
                        title = LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jv_ranking_pi1.name
                        description = LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jv_ranking_pi1.description
                        tt_content_defValues {
                            CType = list
                            list_type = jvranking_pi1
                        }
                    }
                }
                show = *
            }
       }'
    );
		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
		
			$iconRegistry->registerIcon(
				'jv_ranking-plugin-pi1',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:jv_ranking/Resources/Public/Icons/user_plugin_pi1.svg']
			);
		
    }
);
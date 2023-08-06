<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_answer',
        'label' => 'organizer_uid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'answer,organizer_uid,question',
        'iconfile' => 'EXT:jv_ranking/Resources/Public/Icons/tx_jvranking_domain_model_answer.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, answer, organizer_uid, question, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_jvranking_domain_model_answer',
                'foreign_table_where' => 'AND tx_jvranking_domain_model_answer.pid=###CURRENT_PID### AND tx_jvranking_domain_model_answer.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:core/Resources/Private/Language/Resources/Private/Language/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'behaviour' => [
                'allowLanguageSynchronization' => true
            ],
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
            ],
        ],

        'answer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_answer.answer',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int,required'
            ]
        ],
        'organizer_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_answer.organizer_uid',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'question' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_answer.question',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_jvranking_domain_model_question',
                'minitems' => 0,
                'maxitems' => 1,
            ],
        ],
    
    ],
];

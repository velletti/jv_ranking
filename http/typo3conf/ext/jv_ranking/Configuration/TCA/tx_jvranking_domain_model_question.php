<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_question',
        'label' => 'question',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'searchFields' => 'question,description,value,answer',
        'iconfile' => 'EXT:jv_ranking/Resources/Public/Icons/tx_jvranking_domain_model_question.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, question, description, value, answer,valid_until,event_category,tags, visible, access',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, question, description, value, answer ,valid_until,--div--;Extended, valid_until,event_category,tags,access,visible'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_jvranking_domain_model_question',
                'foreign_table_where' => 'AND tx_jvranking_domain_model_question.pid=###CURRENT_PID### AND tx_jvranking_domain_model_question.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/Resources/Private/Language/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],

        'question' => [
            'exclude' => false,
            'label' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_question.question',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'description' => [
            'exclude' => false,
            'label' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_question.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'value' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_question.value',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'sorting' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.sorting',
            'config' => [
                'type' => 'input',
                'size' => 11,
                'eval' => 'int'
            ]
        ],
        'valid_until' => [
            'exclude' => true,
            'label' => 'LLL:EXT:jv_ranking/Resources/Private/Language/locallang_db.xlf:tx_jvranking_domain_model_question.valid_until',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'event_category' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:jv_events/Resources/Private/Language/locallang_db.xlf:tx_jvevents_domain_model_event.event_category',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_jvevents_domain_model_category',

                // 'foreign_table_where' => ' AND tx_jvevents_domain_model_category.type = 0 AND tx_jvevents_domain_model_category.sys_language_uid in (-1, 0)',
                'foreign_table_where' => ' AND tx_jvevents_domain_model_category.type = 0 AND (tx_jvevents_domain_model_category.sys_language_uid = 0 OR tx_jvevents_domain_model_category.l10n_parent = 0) ORDER BY tx_jvevents_domain_model_category.title',
                'itemsProcFunc' => 'JVE\\JvEvents\\UserFunc\\Flexforms->TranslateMMvalues' ,

                'MM' => 'tx_jvranking_question_category_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,

                'fieldControl' => array(
                    'addRecord' => array(
                        'disabled' => false ,
                        'options' => array(
                            'pid' => '###CURRENT_PID###' ,
                            'setValue' => 'prepend' ,
                            'icon' => 'actions-add',
                            'table' => 'tx_jvevents_domain_model_category' ,
                            'title' => 'Create new' ,
                        ),

                    ) ,
                    'editPopup' => array(
                        'disabled' => false ,
                        'options' => array(
                            'icon' => 'actions-open',
                            'windowOpenParameters' => 'height=350,width=580,status=0,menubar=0,scrollbars=1' ,
                            'title' => 'Edit' ,
                        ),
                    ) ,
                ) ,

            ),
        ),
        'tags' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:jv_events/Resources/Private/Language/locallang_db.xlf:tx_jvevents_domain_model_event.tags',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_jvevents_domain_model_tag',
                //'foreign_table_where' => ' AND tx_jvevents_domain_model_tag.sys_language_uid in (-1, ###REC_FIELD_sys_language_uid###)',
                'itemsProcFunc' => 'JVE\\JvEvents\\UserFunc\\Flexforms->TranslateMMvalues' ,
                'foreign_table_where' => ' AND (tx_jvevents_domain_model_tag.sys_language_uid = 0 OR ( tx_jvevents_domain_model_tag.l10n_parent = 0 AND tx_jvevents_domain_model_tag.sys_language_uid in (-1, ###REC_FIELD_sys_language_uid###) )) ORDER BY tx_jvevents_domain_model_tag.name',

                'MM' => 'tx_jvranking_question_tag_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => array(
                    'addRecord' => array(
                        'disabled' => false ,
                        'options' => array(
                            'pid' => '###CURRENT_PID###' ,
                            'setValue' => 'prepend' ,
                            'icon' => 'actions-add',
                            'table' => 'tx_jvevents_domain_model_tag' ,
                            'title' => 'Create new' ,
                        ),

                    ) ,
                    'editPopup' => array(
                        'disabled' => false ,
                        'options' => array(
                            'icon' => 'actions-open',
                            'windowOpenParameters' => 'height=350,width=580,status=0,menubar=0,scrollbars=1' ,
                            'title' => 'Edit' ,
                        ),
                    ) ,
                ) ,
            ),
        ),
        'access' => array(
            'exclude' => 0,
            'label' => 'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.fe_group',
            'config' => array(
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'size' => 5,
                'maxitems' => 1,
                'items' => array(
                    array(
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.any_login',
                        -2
                    ),
                    array(
                        'LLL:EXT:lang/Resources/Private/Language/locallang_general.xlf:LGL.usergroups',
                        '--div--'
                    )
                ),
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
                'foreign_table_where' => 'ORDER BY fe_groups.title',
                'enableMultiSelectFilterTextfield' => true
            )
        ),

        'visible' => array(
            'exclude' => 0,
            'label' => 'Readonly, but visible if no access',
            'config' => array(
                'type' => 'check',
                'default' => 0
            )
        ),

    ],
];

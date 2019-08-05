#
# Table structure for table 'tx_jvranking_domain_model_question'
#
CREATE TABLE tx_jvranking_domain_model_question (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	question varchar(255) DEFAULT '' NOT NULL,
	description text,
	value int(11) DEFAULT '0' NOT NULL,
	answer int(11) unsigned DEFAULT '0' NOT NULL,
	valid_until int(11) unsigned DEFAULT '30' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,

	sorting int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	l10n_state text,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);

#
# Table structure for table 'tx_jvranking_domain_model_answer'
#
CREATE TABLE tx_jvranking_domain_model_answer (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	answer int(11) DEFAULT '0' NOT NULL,
	organizer_uid int(11) DEFAULT '0' NOT NULL,
	question int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
	hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,
	l10n_state text,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY language (l10n_parent,sys_language_uid)

);

## EXTENSION BUILDER DEFAULTS END TOKEN - Everything BEFORE this line is overwritten with the defaults of the extension builder
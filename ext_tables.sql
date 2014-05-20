#
# Structure for table "tx_sqlprofiler_domain_model_run"
#
CREATE TABLE tx_sqlprofiler_domain_model_run (
	uid int(11) NOT NULL auto_increment,
	tstamp int(11) DEFAULT '0' NOT NULL,
	context char(2) DEFAULT 'FE' NOT NULL,
	page_id int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid)
);

#
# Structure for table "tx_sqlprofiler_domain_model_query"
#
CREATE TABLE tx_sqlprofiler_domain_model_query (
	uid int(11) NOT NULL auto_increment,
	run_id int(11) DEFAULT '0' NOT NULL,
	query text,
	count int(11) DEFAULT '0' NOT NULL,
	average_time int(11) DEFAULT '0' NOT NULL,
	PRIMARY KEY (uid)
);

#
# Structure for table "tx_sqlprofiler_domain_model_query_details"
#
CREATE TABLE tx_sqlprofiler_domain_model_query_details (
	uid int(11) NOT NULL auto_increment,
	query_id int(11) DEFAULT '0' NOT NULL,
	execution_time int(11) DEFAULT '0' NOT NULL,
	debug_trail text,
	PRIMARY KEY (uid)
);

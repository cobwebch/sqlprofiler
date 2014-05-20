SQL Profiler
============

This extension overrides TYPO3 CMS base database connection class (`\TYPO3\CMS\Core\Database\DatabaseConnection`)
in order to log all queries executed during a single TYPO3 CMS script run. A BE module is available
to drill down in the collected information.

This is still in an early stage. It works fine but much could be improved, both in terms of filtering
when collecting query information and of the usability of the BE module.


Installation
""""""""""""

Just install the extension and it will start logging everything (except calls to its own
BE module). This means that data quickly accumulates. You should not let this extension
run for a long time. Uninstall it as soon as you have collected enough data.

This extension is compatible only with TYPO3 CMS 6.2.

.. warning::

   Since this extension works by overriding `\TYPO3\CMS\Core\Database\DatabaseConnection`,
   it is not compatible with DBAL.


Configuration
"""""""""""""

There's currently no configuration. It will be added in the future to better helper filter out
what profiling information is stored and in which context.

Currently the following is excluded:

- calls to the SQL Profiler's own BE module
- queries to the SQL Profiler's own tables

It is possible to set the following global variable::

	$GLOBALS['T3_VAR']['tx_sqlprofiler']['skipProfiling'] = TRUE;

to disable profiling for a complete script run.


Viewing the information
"""""""""""""""""""""""

To view the collected information, use the "SQL Profiler" BE module which is found
in the "System" menu.

The main view shows the list of registered runs, with the following information for each:

- the date and time at which the run took place
- the execution context (BE or FE), if defined
- the page for which the profiling happened (if in the FE context)

Clicking on a run's row, you can access the list of queries executed during the run.
For each query, the following information is displayed:

- the number of times it was executed
- the average time all these executions took (in milliseconds)
- the SQL of the query itself

Again clicking on a query's row leads to information about single executions of a given
query. The following information is displayed:

- the time the query took to execute (in milliseconds)
- the debug trail (where the query was executed from)

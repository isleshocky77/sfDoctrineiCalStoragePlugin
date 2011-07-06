<?php
/**
 * PluginsfiCalEvent tests.
 */
include dirname(__FILE__).'/../../../../../../test/bootstrap/unit.php';

$t = new lime_test(null);

$databaseManager = new sfDatabaseManager($configuration);

define('TEST_EVENT_DESCRIPTION', 'Test Event for Testing Purposes #0');

/**
 * cleaniup
 * Cleanup whatever mess way may have made
 */
function cleanup() {

  $table = sfiCalRecurrenceTable::getInstance();
//  $table->createQuery()
//    ->delete()
//    ->execute();
}

cleanup(); # Make sure we cleanup before running test incase it wasn't run last time

$Recurrence = new sfiCalRecurrence();
$Recurrence->frequency = 'daily';
$Recurrence->frequency_interval = 2;

  $Recurrence->save();
try {
  $t->pass('Can successfully save a recurrence');
} catch (Exception $e) {
  $t->fail('Can successfully save a recurrence');
}

$Recurrence = new sfiCalRecurrence();
$Recurrence->frequency = 'blah';
try {
  $Recurrence->save();
  $t->fail('Cannot save a recurrence with an incorrect "frequency" string');
} catch (Exception $e) {
  $t->pass('Cannot save a recurrence with an incorrect "frequency" string');
}


cleanup(); # Be a good neighbor and cleanup when done

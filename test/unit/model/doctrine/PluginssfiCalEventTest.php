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

  $table = sfiCalEventTable::getInstance();
  $table->createQuery()
    ->delete()
    ->addWhere('description LIKE "?%"', TEST_EVENT_DESCRIPTION)
    ->execute();
}

cleanup(); # Make sure we cleanup before running test incase it wasn't run last time

$t->diag('Basic Saving');
$event = new sfiCalEvent();
$event->description = TEST_EVENT_DESCRIPTION.rand();
$event->save();

$saved_event_id = $event->id;

$saved_event = sfiCalEventTable::getInstance()->find($saved_event_id);
$t->is($saved_event->description, $event->description, 'Saved event "description" should be the same as the retrieved events "description"');

$event = new sfiCalEvent();
$event->description = TEST_EVENT_DESCRIPTION.rand();
$event->priority = 12;
$event->status = 'notValid';
try {
  $event->save();
  $t->fail('Cannot save invalid "priority" and "status" properties');
} catch (Doctrine_Validator_Exception $e) {
  $t->pass('Cannot save invalid "priority" and "status" properties');
}

//cleanup(); # Be a good neighbor and cleanup when done

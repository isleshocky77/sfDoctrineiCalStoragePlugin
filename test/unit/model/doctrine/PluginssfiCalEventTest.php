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
$t->is($saved_event->description, $event->description, 'Saved sfiCalEvent->"description" should be the same as the retrieved sfiCalEvent->"description"');

$event = new sfiCalEvent();
$event->description = TEST_EVENT_DESCRIPTION.rand();
$event->priority = 12;
try {
  $event->save();
  $t->fail('Cannot save "sfiCalEvent" with an invalid "priority" number');
} catch (Doctrine_Validator_Exception $e) {
  $t->pass('Cannot save "sfiCalEvent" with an invalid "priority" number');
}

$event = new sfiCalEvent();
$event->description = TEST_EVENT_DESCRIPTION.rand();
$event->status = 'notValid';
try {
  $event->save();
  $t->fail('Cannot save an "sfiCalEvent" with an invalid "status" string');
} catch (Doctrine_Validator_Exception $e) {
  $t->pass('Cannot save an "sfiCalEvent" with an invalid "status" string');
}

$event = new sfiCalEvent();
$event->description = TEST_EVENT_DESCRIPTION.rand();
$event->location = 'Test Location';
$event->Recurrence->frequency = 'daily';
$event->Recurrence->frequency_interval = 2;
$event->save();

$Event = sfiCalEventTable::getInstance()->find($event->id);
$t->isa_ok($Event, 'sfiCalEvent', '$Event is of type "sfiCalEvent"');
$t->isa_ok($Event->Recurrence, 'sfiCalRecurrence', '$Event->Recurrence is of type "sfiCalRecurrence"');

$Recurrence = sfiCalRecurrenceTable::getInstance()->find($Event->Recurrence);
$t->isa_ok($Recurrence, 'sfiCalRecurrence', '$Recurrence is of type "sfiCalRecurrence');
$t->isa_ok($Recurrence->Event, 'sfiCalEvent', '$Recurrence->Event is of type of "sfiCaleEvent"');
$t->is($Recurrence->Event->location, 'Test Location', '$Recurrence->Event->"location" is correct from the sfiCalEvent');

cleanup(); # Be a good neighbor and cleanup when done

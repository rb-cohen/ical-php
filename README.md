# ical-php
Generate an iCal file by providing event objects

## Example
```php
<?php

use Ical\Feed;
use Ical\Component\Calendar;
use Ical\Component\Event;

// One feed (i.e. URL) can host more than one caldenar, lets create a feed
$feed = new Feed();

// This calendar will contain our events
$calendar = new Calendar('ical-example//v1');

$event = new Event('uid-1@example');
$event->created(new DateTime('2015-01-01'));
$event->lastModified(new DateTime('2015-01-05'));
$event->between(new DateTime('2015-04-01'), new DateTime('2015-04-01'));
$event->summary('Example of an event');
$event->allDay(true);

// Add this event to the calendar
$calendar->addEvent($event);

// Output the feed with appropriate HTTP header
$feed->output();
exit;
```

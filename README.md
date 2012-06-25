BazingaPropelEventDispatcherBundle
==================================

Integrates the Propel
[EventDispatcherBehavior](https://github.com/willdurand/EventDispatcherBehavior)
into Symfony2.

Each class owns its own event dispatcher, so that you don't listen useless
events, and you have a better separation of concerns.


### Installation

1. Register the bundle as usual.
2. Add the EventDispatcherBehavior to your model classes.
3. Configure your listeners.


### Usage

You need a simple class which acts as a Listener. In the following code, you
have an `ObjectListener` class which is a listener for `Object` instances:

```php
<?php

namespace My\Bundle\Listener;

use Symfony\Component\EventDispatcher\Event;

class ObjectListener
{
    public function preSave(Event $event)
    {
        // do what you want with $event
        // for instance, $event->geSubject() will return the Object object
    }
}
```

In order to use this listener, you have to register a service in the Dependency
Injection Container provided by Symfony2:

```xml
<service id="my_bundle.listener.event_listener" class="%my_bundle.listener.event_listener.class%">
    <tag name="propel.event_listener" class="My\Bundle\Model\Object" event="propel.pre_save" />
</service>
```

**Note:** You have to tag this service with `propel.event_listener`, and both
`class`, and `event` are required.

The event `propel.pre_save` will call the method named `preSave()`. This is a
convention. And, all "core" events use it (`propel.post_update` will call the
method `postUpdate()`).
If you register your own event, let's say `preStart`, it will call the
`preStart()` method, and an event named `pre_start` will also call `preSave()`.

That's all folks!

BazingaPropelEventDispatcherBundle
==================================

[![Build Status](https://secure.travis-ci.org/willdurand/BazingaPropelEventDispatcherBundle.png?branch=master)](http://travis-ci.org/willdurand/BazingaPropelEventDispatcherBundle)

Integrates the Propel
[EventDispatcherBehavior](https://github.com/willdurand/EventDispatcherBehavior)
into Symfony2.

Each class owns its own event dispatcher, so that you don't listen to useless
events, and you have a better separation of concerns.


### Installation

Register the bundle as usual:

```js
{
    "require": {
        "willdurand/propel-eventdispatcher-bundle": "dev-master"
    }
}
```

In `app/AppKernel.php`:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new Bazinga\Bundle\PropelEventDispatcherBundle\BazingaPropelEventDispatcherBundle(),
    );

    // ...
}
```

Add the EventDispatcherBehavior to your model classes. See the documentation of
the EventDispatcherBehavior.

Finally, configure your listeners.


### Usage

You need a simple class which acts as a Listener. In the following code, you
have an `ObjectListener` class which is a listener for `Object` instances:

```php
<?php
// src/My/Bundle/Listener/ObjectListener.php

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
// src/My/Bundle/Resources/config/services.xml

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
`preStart()` method, and an event named `pre_start` will also call `preStart()`.

**Note:** If you want to use a method name different than the convention, you
can force it by adding the `method` attribute on the tag.

You can add as many tags as you want in order to register more than one event:

```xml
// src/My/Bundle/Resources/config/services.xml

<service id="my_bundle.listener.event_listener" class="%my_bundle.listener.event_listener.class%">
    <tag name="propel.event_listener" class="My\Bundle\Model\Object" event="propel.pre_save" />
    <tag name="propel.event_listener" class="My\Bundle\Model\Object" event="propel.post_save" />
    <tag name="propel.event_listener" class="My\Bundle\Model\Object" event="propel.post_save" method="latePostSave" priority="-10" />
</service>
```

The bundle also supports registering event subscribers:

```xml
// src/My/Bundle/Resources/config/services.xml

<service id="my_bundle.listener.event_listener" class="%my_bundle.listener.event_listener.class%">
    <tag name="propel.event_subscriber" class="My\Bundle\Model\Object" />
</service>
```

**Note:** You have to tag this service with `propel.event_subscriber`, and the
`class` is required. The class of the service must also be defined properly
even when the service is created by a factory.

Read more about the Propel [lifecycle
callbacks](http://www.propelorm.org/cookbook/symfony2/symfony2-and-propel-in-real-life.html#lifecycle_callbacks)
to know all available hooks, and their differences.

That's all folks!

# bernard-example

This is an example demonstrating the use of [Bernard](http://bernard.readthedocs.io/) with a Pimple dependency injection container. It allows you, in a slightly contrived way, to queue greeting people in either English or French. The aim is to show how Bernard allows dispatching a message to a service that is constructed with dependencies from the container.

To run this demo, open clone this repo and install its dependencies using Composer:

```
$ git clone https://github.com/rpalladino/bernard-example.git
$ cd bernard-example
$ composer install
```

Then open three console windows. In the first, start a worker to greet people in English:

```
$ bin/greet english
```

In the second, start another worker to greet people in French:

```
$ bin/greet french
```

Finally, in the third, queue some greetings:

```
$ bin/queue-greeting John english
$ bin/queue-greeting Jacques french
```

As you queue a greeting, you should see the greeting appear in one of the two worker consoles.

# Message bus [![Build Status](https://travis-ci.org/zelenin/message-bus.svg?branch=master)](https://travis-ci.org/zelenin/message-bus) [![Coverage Status](https://coveralls.io/repos/github/zelenin/message-bus/badge.svg?branch=master)](https://coveralls.io/github/zelenin/message-bus?branch=master)

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

```
php composer.phar require zelenin/message-bus "dev-master"
```

or add

```
"zelenin/message-bus": "dev-master"
```

to the require section of your ```composer.json```

## Usage

### Example

```php
$handlers = [
    CreatePost::class => new CreatePostHandler($postRepository)
];

$middlewares = [
    new HandlerMiddleware(new MemoryLocator($handlers))
];

$commandBus = new MiddlewareBus(new MiddlewareStack($middlewares));

$message = new CreatePost('Post title', 'Post content');

$context = $commandBus->handle($message);
```

```ContainerLocator``` may be used for [PSR-11 Container](https://github.com/php-fig/container) support.

### Context

The Message bus uses the immutable ```Context``` to transfer data through middlewares and return it to consumer. 

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)

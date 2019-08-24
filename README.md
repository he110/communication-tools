# Communication Tools [![Build Status](https://travis-ci.com/he110/communication-tools.svg?branch=master)](https://travis-ci.com/he110/communication-tools)

[![Latest Stable Version](https://img.shields.io/packagist/v/he110/communication-tools.svg)](https://packagist.org/packages/he110/communication-tools) [![codecov](https://codecov.io/gh/he110/communication-tools/branch/master/graph/badge.svg)](https://codecov.io/gh/he110/communication-tools) [![Maintainability](https://api.codeclimate.com/v1/badges/8fba6456c0c825fc252a/maintainability)](https://codeclimate.com/github/he110/communication-tools/maintainability)

Tools set for messenger managing. Allows you to send any content via Telegram, Viber, WhatsApp, VK, Facebook Messenger and so on.

## Installation

Install the latest version with

```bash
$ composer require he110/communication-tools
```

## Basic Usage

### Messenger's clients
```php
<?php

// Telegram client's taken as an example. You can use other
use He110\CommunicationTools\Telegram\Messenger;
use He110\CommunicationTools\MessengerScreen;

$messenger = new Messenger();
$messenger->setAccessToken(YOUR_TOKEN_HERE);

// If you want, to send simple text message
$messenger->sendMessage("Your message text here");

// To send image use method sendImage
$messenger->sendImage("path/to/file", "(Optional) Your text description");
// or, to send document...
$messenger->sendDocument("path/to/file", "(Optional) Your text description");
// you can also send voice files
$messenger->sendVoice("path/to/file");

// If you wanna use buttons, it's better way to use MessengerScreen
$screen = new MessengerScreen();
$screen->addMessage("Your message text here");
$screen->addButtonText("Text button");
$screen->addButtonLink("URL button", "https://google.com");
$messenger->sendScreen($screen);

```

### Multiple messengers
```php
<?php

// Telegram client's taken as an example. You can use other
use He110\CommunicationTools\MessengerPool;
use He110\CommunicationTools\Telegram\Messenger;
use He110\CommunicationTools\MessengerScreen;

$messenger = new Messenger();
$messenger->setAccessToken(YOUR_TOKEN_HERE);

// Pool allows you to use multiple messengers as one
$pool = new MessengerPool();
$pool->add($messenger);

$pool->sendMessage("Your message text here");

// If you wanna use buttons, it's better way to use MessengerScreen
$screen = new MessengerScreen();
$screen->addMessage("Your message text here");
$screen->addButtonText("Text button");
$screen->addButtonLink("URL button", "https://google.com");
$pool->sendScreen($screen);

```


### Work with events
```php
<?php

// Telegram client's taken as an example. You can use other
use He110\CommunicationTools\Telegram\Messenger;
use He110\CommunicationTools\Request;
use He110\CommunicationTools\MessengerUser;

$messenger = new Messenger();
$messenger->setAccessToken(YOUR_TOKEN_HERE);

// Action for simple incoming messages
$messenger->onMessage(function(Request $request) use ($messenger) {
    // Your code here...
    $text = $request->getMessage();
    /** @var MessengerUser $user $user */
    $user = $request->getUser();
    $messenger->setTargetUser($user->getUserId());
    $messenger->sendMessage("We've got your message: '$text'");
});

// Action for buttons click
$messenger->onButtonClick(function(Request $request) use ($messenger) {
     // Your code here...
     $payload = $request->getPayload();
});

// Required!!! Run this method to check if events are triggered
$messenger->checkEvents();

```

## About

### Requirements

- Communication Tools works with PHP 7.2 or above.

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/he110/communication-tools/issues)

### Author

Ilya S. Zobenko - <ilya@zobenko.ru> - <http://twitter.com/he110_todd>

### License

"Communication Tools" is licensed under the MIT License - see the `LICENSE` file for detail

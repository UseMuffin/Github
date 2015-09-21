# Github

[![Build Status](https://img.shields.io/travis/UseMuffin/Github/master.svg?style=flat-square)](https://travis-ci.org/UseMuffin/Github)
[![Coverage](https://img.shields.io/coveralls/UseMuffin/Github/master.svg?style=flat-square)](https://coveralls.io/r/UseMuffin/Github)
[![Total Downloads](https://img.shields.io/packagist/dt/muffin/github.svg?style=flat-square)](https://packagist.org/packages/muffin/github)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

Github Webservice (API) for CakePHP 3.

## Install

Using [Composer][composer]:

```
composer require muffin/github:dev-master
```

You then need to load the plugin. You can use the shell command:

```
bin/cake plugin load Muffin/Github
```

or by manually adding statement shown below to `boostrap.php`:

```php
Plugin::load('Muffin/Github');
```

## Usage

In your `app.php`, configure your `github` service like any other configuration, by adding a new element to the configure array:

```php

    'Webservices' => [
        'github' => [
            'className' => 'Muffin\Webservice\Connection',
            'service' => 'Muffin/Github.Github',
        ]
    ]
```

or, to use a token for example (and full namespaced driver):

```php

    'Webservices' => [
        'github' => [
            'className' => 'Muffin\Webservice\Connection',
            'service' => 'Muffin\Github\Webservice\Driver\Github',
            'token' => env('GITHUB_TOKEN'),
        ]
    ]
```

or using your username/password combination:

```php

    'Webservices' => [
        'github' => [
            'className' => 'Muffin\Webservice\Connection',
            'service' => 'Muffin/Github.Github',
            'username' => env('GITHUB_USERNAME'),
            'password' => env('GITHUB_PASSWORD'),
        ]
    ]
```

or using your client ID and secret:

```php

    'Webservices' => [
        'github' => [
            'className' => 'Muffin\Webservice\Connection',
            'service' => 'Muffin/Github.Github',
            'clientId' => env('GITHUB_CLIENT_ID'),
            'secret' => env('GITHUB_SECRET'),
        ]
    ]
```

You will also need to load the webservices if you haven't already done that in your
`bootstrap.php` file:

```php
ConnectionManager::config(Configure::consume('Webservices'));
```

Now, from anywhere, you could call the webservice like so:

```php
$connection = ConnectionManager:get('github');
$repo = $connection->api('repo')->show('usemuffin', 'github');
```

## Patches & Features

* Fork
* Mod, fix
* Test - this is important, so it's not unintentionally broken
* Commit - do not mess with license, todo, version, etc. (if you do change any, bump them into commits of
their own that I can ignore when I pull)
* Pull request - bonus point for topic branches

To ensure your PRs are considered for upstream, you MUST follow the CakePHP coding standards.

## Bugs & Feedback

http://github.com/usemuffin/github/issues

## License

Copyright (c) 2015, [Use Muffin] and licensed under [The MIT License][mit].

[cakephp]:http://cakephp.org
[composer]:http://getcomposer.org
[mit]:http://www.opensource.org/licenses/mit-license.php
[muffin]:http://usemuffin.com

# Html-Form
PHP class for html form builder

## Requirements

- PHP 5.4.0 or above

## Installation

### Composer

[Composer](https://getcomposer.org/) is a widely used dependency manager for PHP
packages. This Html-Form is available on Packagist as
[`user-meta/html`](https://packagist.org/packages/user-meta/html) and can be
installed either by running the `composer require` command or adding the library
to your `composer.json`. To enable Composer for you project, refer to the
project's [Getting Started](https://getcomposer.org/doc/00-intro.md)
documentation.

To add this dependency using the command, run the following from within your
project directory:
```
composer require user-meta/html "dev-master"
```

Alternatively, add the dependency directly to your `composer.json` file:
```json
"require": {
    "user-meta/html": "dev-master"
}
```

## Get Started
A quick example of generating text input with Html-Form using composer:

```
<?php
require __DIR__ . '/vendor/autoload.php';

use UserMeta\Html\Html;
echo Html::text('Some text');
```


## More Examples

```

/**
 * Create a text field with default value
 */
echo Html::text('Some text');

/**
 * Create a text field with default value, name, id and class attributes
 */
echo Html::text('Some text', ['name' => 'Name', 'id' => 'ID', 'class' => 'Class']);

/**
 * Create an email input field
 */
echo Html::input('email', 'noreply@gmail.com');

/**
 * Create a checkbox with default checked and with name and id attributes
 */
echo Html::checkbox(true, ['name' => 'Name', 'id' => 'ID']);

/**
 * Create a list of checkboxes with default values
 */
echo Html::checkboxList(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);

/**
 * Create a select with default value, name and id attributes
 */
echo Html::select(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);

/**
 * Alies select
 */
echo Html::dropdown(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);

/**
 * Create a list of radio
 */
echo Html::radio(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);

/**
 * Create a lebel with label text, id, class and for attributes
 */
echo Html::label('Some text', ['id' => 'ID', 'class' => 'Class', 'for' => 'for']);
```

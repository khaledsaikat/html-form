# Html-Form

PHP class to generate html elements

- License: MIT

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

Next, update Composer from the Terminal:

```
composer update
```

## Get Started

A quick example of generating text input with Html-Form using composer:

```php
<?php
require __DIR__ . '/vendor/autoload.php';
use UserMeta\Html\Html;
echo Html::text('example');
```

Output:

```html
<input type="text" value="example"/>
```

Almost all types of html element can be used. (e.g: buttom, email, div, p etc)

```php
echo Html::button('Submit Me');
echo Html::email('example email');
echo Html::div('example text');
echo Html::p('example text');
```

Output:

```html
<input type="button" value="Submit Me"/>
<input type="email" value="example email"/>
<div>example text</div>
<p>example text</p>
```

## Basic Usage

Most of the element accept two arguments:
- `$default`: Default value
- `$attributes`: Array of attributes
- `echo Html::text($default, attributes);`

### using name, id and class
To assign name, id, class or any other attributes, use second arguments (`$attributes`)

A text field with default value, name, id and class attributes:

```php
echo Html::text('Example_Value', ['name' => 'Example_Name', 'id' => 'Example_ID', 'class' => 'Example_Class']);
```

Output:

```html
<input type="text" name="Example_Name" value="Example_Value" id="Example_ID" class="Example_Class"/>
```

You can also add any attributes into any fields:

```php
echo Html::text('Example_Value', ['name' => 'Example_Name', 'data-example' => 'Example_Data']);
```

Output:

```html
<input type="text" name="Example_Name" value="Example_Value" data-example="Example_Data"/>
```

Using label with input:

```php
echo Html::email(null, ['name' => 'Example_Name', 'label' => 'Email']);
```

Output:

```html
<label>Email</label>
<input type="email" name="Example_Name"/>
```

A div  with id and class attributes:

```php
echo Html::div('example text', ['id' => 'Example_ID', 'class' => 'Example_Class']);
```

Output:

```html
<div id="Example_ID" class="Example_Class">example text</div>
```

### Using collection

Several elements can be grouped together as collection

```php
$div = new Html('div');
$div->p('Hello World');
$div->text('example');
$div->add('Some plain text');
echo $div->render();
```

Output:

```html
<div>
    <p>Hello World</p>
    <input type="text" value="example"/>
    Some plain text
</div>
```

#### Form example

Generating a form using collections:

```php
$form = new Html('form', ['method' => 'POST']);
$form->div('Enter your email and password for login');
$form->email('', ['name' => 'email', 'label' => 'Email']);
$form->password('', ['name' => 'password', 'label' => 'Password']);
$form->submit('login');
echo $form->render();
```

Output:

```html
<form method="POST">
    <div>Enter your email and password for login</div>
    <label>Email</label><input type="email" name="email"/>
    <label>Password</label><input type="password" name="password"/>
    <input type="submit" value="login"/>
</form>
```

#### Nested collections

Generating html template using nested collections:

```php
$html = new Html('html');
$head = new Html('head');
$head->title('Example Title');
$html->add($head);
$body = new Html('body');
$body->p('Hello World');
$html->add($body);
echo $html->render();
```

```html
<html>
    <head>
        <title>Example Title</title>
    </head>
    <body>
        <p>Hello World</p>
    </body>
</html>
```

## More Examples

Although it is possible to create any element by calling their name. But under the hood, we use `Html::input()` for input element and `Html::tag()` for html tag

Create an email input by using `input` method:

```php
echo Html::input('email', 'noreply@gmail.com');
```

Create h1 by using `tag` method:

```php
echo Html::tag('h1', 'Example Heading');
```

Create a checkbox with default checked and with name and id attributes
```php
echo Html::checkbox(true, ['name' => 'Name', 'id' => 'ID']);
```

Create a list of checkboxes with default values
```php
echo Html::checkboxList(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);
```

Create a select with default value, name and id attributes
```php
echo Html::select(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);
```

Alies select
```php
echo Html::dropdown(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);
```

Create a list of radio
```php
echo Html::radio(['cat'], ['name' => 'Name', 'id' => 'ID'], ['dog' => 'Dog', 'cat' => 'Cat']);
```

Create a lebel with label text, id, class and for attributes
```php
echo Html::label('Some text', ['id' => 'ID', 'class' => 'Class', 'for' => 'for']);
```

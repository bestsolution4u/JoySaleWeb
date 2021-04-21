Toastr widget for Yii2 framework
=================

## Description

toastr is a Javascript library for Gnome / Growl type non-blocking notifications. jQuery is required. The goal is to create a simple core library that can be customized and extended.
For more information please visit [Toastr](http://codeseven.github.io/toastr/) 

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

To install, either run

```
$ php composer.phar require conquer/toastr "*"
```
or add

```
"conquer/toastr": "*"
```

to the ```require``` section of your `composer.json` file.

## Usage

```php
use conquer\toastr\ToastrWidget;

ToastrWidget::widget(['title'=>'Hello!', 'message'=>'World!']);

```

## License

**conquer/toastr** is released under the MIT License. See the bundled `LICENSE.md` for details.
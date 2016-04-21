yii2-enlog
=
yii2 component for Enlog v1 API implementation

#### Requirements
* Yii 2 Framework
* [Enlog Account](enlog.net)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```json
php composer.phar require --prefer-dist shinomontaz/yii2-enlog "*"
```

or add

```json
"shinomontaz/yii2-enlog": "*"
```

to the require section of your `composer.json` file.

#### Configure
main.php:
```php
...
'components' => [
	'enlog' => [
    'class' => 'shinomontaz\Enlog',
		'url' =>	'https://api.enlog.net',
		'name' =>	'YOUR ENLOG USER NAME',
		'pass' =>	'YOUR ENLOG USER PASS',
	],
	...
```

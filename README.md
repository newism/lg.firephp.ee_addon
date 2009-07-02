LG FirePHP
==========

LG FirePHP is an [ExpressionEngine](http://expressionengine.com) extension that implements the [FirePHP](http://www.firephp.org/) debugging framework.

FirePHP enables you to log to your [Firebug console](http://getfirebug.com) using a simple PHP method call. All data is sent via response headers and will not interfere with the content on your page. FirePHP is ideally suited for AJAX development where clean JSON and XML responses are required.

LG FirePHP was written by [Leevi Graham](http://leevigraham.com), Technical Director of Newcastle based web design and development company [Newism](http://newism.com.au).

Requirements
------------

* [PHP 5](http://php.net)
* [Firefox](http://getfirefox.com)
* [Firebug](http://getfirebug.com)
* [FirePHP](http://www.firephp.org/)
	
Installation
------------

1. Install Firefox
2. Install Firebug
3. Install FirePHP
4. Enable Firebug and the console
5. Add your site to the allowed site in the FirePHP (firebug extension) settings.
6. Copy `system/extensions/ext.lg_fire_ext.php` to your `system/extensions` directory
7. Copy `system/extensions/lg_firephp_ext` to your `system/extensions` directory
8. Copy `system/language/english/lang.lg_firephp_ext.php` to your `system/language/english` directory
9. Enable the extension

If enabled correctly you should see some sample FirePHP output in the Firebug console.

For developers
--------------

LG FirePHP implements the FirePHP Object Oriented API. 

Examples:

	FB::log('Log message');
	FB::info('Info message');
	FB::warn('Warn message');
	FB::error('Error message');

Outputs:

![FireBug FirePHP output](http://www.firephp.org/images/Screenshots/SimpleConsole.png)

There's also

	FB::dump("Email data", $email_data);

More information: [FirePHP HQ](http://www.firephp.org/HQ/Use.htm)
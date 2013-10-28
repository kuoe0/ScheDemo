PresentReg
==========

PresentReg is a web-based application for presentation scheduling.

Requirement
-----------

- A Computer as Server
- Apache Web Server with Rewrite Module Enabled
- PHP5 with PDO-sqlite Driver

Server Settings
---------------

**Setup Apache Web Server**

Ubuntu 13.04:

First, install apache web server.

	$ apt-get install apache

And then, enable the rewrite module.

	$ a2enmod rewrite

Finally, restart the Apache web service.

	$ service apache restart

OS X 10.9:

Apache Web Server is built-in OS X. So, just type the command below to launch it.

	$ sudo apachectl start

And then, enable the rewrite module. Open the file `/etc/apache2/httpd.conf` and uncomment this line below.

	$ LoadModule rewrite_module libexec/apache2/mod_rewrite.so

Finally, restart the Apache web server.

	$ sudo apachectl restart

**Setup PHP5 with PDO-sqlite Driver**

Ubuntu 13.04:

	apt-get install php5-sqlite

OS X 10.9:

**Directory Permission**

First, download the source code.
	
	git clone http://github.com/KuoE0/PresentReg

And then, set the group of this directory to `www-data` or the group of apache.
	
	chgrp www-data PresentReg

To-do
-----

1. authorization for presenters, e.g. email, CAPTCHA
2. minimize the register form to view entire table



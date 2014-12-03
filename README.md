MailPanel
===========

A panel for Tracy Debugger that show output of both html and plaintext versions of emails sent by Atk14 ApplicationMailer.

Basic usage
-----------

```php
$bar = Tracy\Debugger::getBar();
$bar->addPanel(new MailPanel($this->mailer));
```

Usage in an ATK14 application (built upon Atk14Skelet)
------------------------------------------------------

First enable Tracy Debugger.

```php
// file: lib/load.php
if(
  !TEST &&
  !$HTTP_REQUEST->xhr() &&
  php_sapi_name()!="cli" // we do not want Tracy in cli
){
  Tracy\Debugger::enable(PRODUCTION, __DIR__ . '/../log/');
}
```

Add MailPanel panel to Tracy in \_application_after_filter().

```php
// file: app/controllers/application_base.php
function _application_after_filter(){
  if(!TEST){
    $bar = Tracy\Debugger::getBar();
    $bar->addPanel(new MailPanel($this->mailer));
  }
}
```

Installation
------------

Just use the Composer:

```
$ cd path/to/your/atk14/project/
$ php composer.phar require atk14/mail-panel dev-master
```

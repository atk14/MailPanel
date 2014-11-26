DbMolePanel
===========

A panel for Tracy Debugger with DbMole statistics

Basic usage
-----------

```php
$bar = Tracy\Debugger::getBar();
$bar->addPanel(new DbMolePanel($dbmole));
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

Enable collecting DbMole statistics in DEVELOPMENT.

```php
// file: config/settings.php
define("DBMOLE_COLLECT_STATICTICS",DEVELOPMENT);
```

Add DbMole panel to Tracy in \_application_after_filter().

```php
// file: app/controllers/application_base.php
function _application_after_filter(){
  if(DBMOLE_COLLECT_STATICTICS){
    $bar = Tracy\Debugger::getBar();
    $bar->addPanel(new DbMolePanel($this->dbmole));
  }
}
```

Installation
------------

Just use the Composer:

```
$ cd path/to/your/atk14/project/
$ php composer.phar require atk14/dbmole-panel dev-master
```

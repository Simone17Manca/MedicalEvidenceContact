@echo off
set ROOT=%~dp0
set PHPRC=%ROOT%php-local.ini
"%ROOT%tools\php-8.4\php.exe" -c "%ROOT%php-local.ini" "%ROOT%tools\composer.phar" %*

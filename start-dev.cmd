@echo off
set ROOT=%~dp0
set PHPRC=%ROOT%php-local.ini
start "" /B "C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqld.exe" --defaults-file="%ROOT%mysql-dev.ini"
timeout /t 5 /nobreak > nul
"%ROOT%tools\php-8.4\php.exe" -c "%ROOT%php-local.ini" -S 127.0.0.1:8000 -t public

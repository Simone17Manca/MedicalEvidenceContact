$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$Php = Join-Path $Root 'tools\php-8.4\php.exe'
$Mysql = 'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqld.exe'
$MysqlIni = Join-Path $Root 'mysql-dev.ini'
$env:PHPRC = Join-Path $Root 'php-local.ini'

Start-Process -FilePath $Mysql -ArgumentList "--defaults-file=$MysqlIni" -WindowStyle Hidden
Start-Sleep -Seconds 5
& $Php -c (Join-Path $Root 'php-local.ini') -S 127.0.0.1:8000 -t public

$Root = Split-Path -Parent $MyInvocation.MyCommand.Path
$Php = Join-Path $Root 'tools\php-8.4\php.exe'
$Composer = Join-Path $Root 'tools\composer.phar'
$env:PHPRC = Join-Path $Root 'php-local.ini'
& $Php -c (Join-Path $Root 'php-local.ini') $Composer @args

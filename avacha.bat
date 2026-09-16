@echo off
set "PORT=3000"
echo Avacha app starts in developer mode (:%PORT%)
powershell "php -S "127.0.0.1:%PORT%" -t "./public" > $null"
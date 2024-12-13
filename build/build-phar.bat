@echo off
setlocal enabledelayedexpansion

REM Get path to this script
for %%I in ("%~dp0.") do set SCRIPT_DIR=%%~fI

php -c "!SCRIPT_DIR!\php.ini" "!SCRIPT_DIR!\..\vendor\phing\phing\bin\phing.php" %*

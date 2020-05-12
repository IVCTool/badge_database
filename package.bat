ECHO OFF
REM This is just a convienicen to quickly generate the plugin zip package.
REM It uses 7Zip but you need to say where to find it.
TITLE Package up the WP plugin

REM This is where 7Zip is found on your machine
SET LOCATION="C:\Program Files\7-Zip"

REM delte the old file if it exists
ECHO Old plugin zip file deleted
del /f /q badgedb-plugin.zip

REM PAUSE 

REM And generate the new one.
%LOCATION%\7z.exe a badgedb-plugin.zip badgedb-plugin\

REM PAUSE
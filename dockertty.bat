@echo off

cd vendor\j4mie\idiorm
set realpath=%cd%

@echo on
@echo %realpath%

docker run -it -v %realpath%:/tmp/idiorm --rm treffynnon/php5.2cli

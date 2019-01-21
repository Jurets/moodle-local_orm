@echo off

cd vendor\j4mie\idiorm
set realpath=%cd%

@echo on
@echo %realpath%

docker run -t -v %realpath%:/tmp/idiorm --rm treffynnon/php5.2cli /root/phpunit -c /tmp/idiorm/phpunit.xml

@echo off
cd ../../..
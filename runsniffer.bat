@echo off

cd ../..

@echo on

php local\codechecker\pear\PHP\scripts\phpcs --standard=moodle --ignore=*/vendor/* local\orm

@echo off
cd local\orm
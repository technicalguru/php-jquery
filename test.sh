# Test script

composer update
./vendor/phpunit/phpunit/phpunit tests
RC=$?

# Additional handling of this dir
rm -rf js

exit $RC


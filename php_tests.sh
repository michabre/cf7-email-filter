#!/bin/bash

# run phpunit tests
# $1 = test type

case $1 in

  basic)
    ./vendor/bin/phpunit tests
    ;;

  testdox)
    ./vendor/bin/phpunit --testdox tests
    ;;
  
  file)
    ./vendor/bin/phpunit --testdox --filter $2 tests
    ;;

  *)
    echo -n "unknown"
    ;;
    
esac
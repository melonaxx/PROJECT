#!/bin/bash
echo "****************************************************"
echo "****************This is Demo Shell Scrite***********"
echo $PRJ_ROOT

case $1 in
    config)
        echo " Exec Config "
        exit;
        ;;
    data)
        echo " Exec Data "
        exit;
        ;;
    start)
        echo " Exec start "
        exit;
        ;;
    stop)
        echo " Exec stop "
        exit;
        ;;
esac

echo "*****************End***********"


RG=/home/q/tools/sundial_rigger/rigger
SCI_PATH=`dirname $0`
CUR_PATH=$SCI_PATH/
$RG -s test phpunit -f $CUR_PATH

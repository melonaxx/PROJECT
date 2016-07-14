export PATH=.:/sbin:/usr/sbin:/usr/local/sbin:/usr/local/bin:/bin:/usr/bin:/usr/local/bin
SCI_PATH=`dirname $0`
CUR_PATH=$SCI_PATH/
RG=/home/q/tools/sundial_rigger/rigger
$RG  conf -e dev -s all 
$RG  restart

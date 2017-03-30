#!/bin/bash
#each task run about 20 second
timeWaitPerTask=20 
taskName='job_getPeopleDetail.sh\|job_getCompanyDetail.sh'

LIST_PID=$(ps -ef | grep $taskName | grep -v grep | awk '{print $2}')
CURRENT_PID=$$
for pid in $LIST_PID
do
	if [ "$CURRENT_PID" -ne "$pid" ]
		then
		#kill preview task running before start new job
		kill -9 $pid
	fi
done

#Each hour we run this script 1 time 
#Each minute we run 3 task . each task separate 20 second 
#So each hour we run total 180 time task
for run in {1..180}
do
	/usr/bin/php /virtual/works/public_html/cat_cb/oil r app:appGetPeopleDetail > /dev/null 2>&1
	sleep $timeWaitPerTask
done

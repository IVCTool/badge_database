# This is a Windows Powershell script for cleaning up the whole stack
docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)
docker volume prune
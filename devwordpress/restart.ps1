#This is a Windows Powershell script that effectively restarts the docker containers.
docker stop $(docker ps -a -q)
docker rm $(docker ps -a -q)
docker-compose up -d
##install docker-compose
https://docs.docker.com/compose/install/#install-compose
https://stackoverflow.com/questions/29101043/cant-connect-to-docker-from-docker-compose/29111083


Port 8080: Nginx for editor access
Port 81: Nginx will redirect to https
Port 80: Varnish will fetch from port 80 and removes all cookies and caches everyting for 1 hour


#kill a all running docker processes if u got mysql problems
docker kill $(docker ps -q)


#performance tips 
#not sure if this helps
https://gist.github.com/kortina/67ad6e40e40d5199c3507cdad0c9a12c


##run manually
docker run -p 9000:9000 exporo-cms/php 
docker run -p 8080:80 exporo-cms/nginx 
docker run --env BACKENDS=127.0.0.1:8080 -p 6081:6081 exporo-cms/varnish 

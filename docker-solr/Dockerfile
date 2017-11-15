FROM ubuntu:16.04
RUN apt-get update && apt-get install -y \
    openjdk-8-jre \
    unzip \
    lsof
EXPOSE 8983
ENTRYPOINT /var/solr/bin/solr start -f -m 256m


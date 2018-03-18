FROM ubuntu:16.04

RUN apt-get update && apt-get install -y python3-virtualenv python3-dev python3-pip

RUN pip3 install gunicorn flask requests

WORKDIR /app
EXPOSE 5001
ENTRYPOINT gunicorn duss:app --bind 0.0.0.0:5001
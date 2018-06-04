# Index Iuris

## Overview

The project consists of four main elements:
- PHP website frontend w/ admin interface
- Flask api for searching
- Solr backend for actual indexing and searching
- MySQL database for storing objects, users, and submission data

## Installation

After cloning, you will need to edit/rename the appropriate config files:

    /flask/config.py
    /web/includes/config.php

You will also need to set up a MySQL database and add the latest data dump. Add this configuration to the `config.php` file.

The recommended method of installation is through `docker-compose` 

    cd indexIuris
    docker-compose build
    docker-compose up

This should start three docker containers, one for the PHP web frontend, one for the flask searching api, and one for the solr instance.

## Management

To manage the web interface you will need to sign up/log in to the admin section of the website. Regular users are able to make submissions and manage their own submissions. Superusers are allowed to view all submissions. To create a superuser, you will first need to create a regular user and then add the superuser flag on the `users` database. 

Because the app handles user emails and passwords, and because users may use the same password for other service, the `web` service should be served over https. The docker container for the web service should work fine behind a proxy, if https is set up on the host.

### Indexing

To index/reindex any submissions, visit the /update-all.php page through the browser (while logged in as a superuser). This will update the solr index with any new data, and make it available for searching.
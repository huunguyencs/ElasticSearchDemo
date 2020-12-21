# ElasticSearch Demo

## Quick start

### Install environment (on Ubuntu 20.04)

#### Elastic search


```bash
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-7.10.1-linux-x86_64.tar.gz
tar -xzf elasticsearch-7.10.1-linux-x86_64.tar.gz
```


#### Install php 7.4 and php-curl

```bash
# Install php 7.4
sudo apt install php7.4-cli
# Install php-curl for php
sudo apt-get install php-curl
```

### Run

```bash
# First, run elastic search server (default on port 9200)
cd elasticsearch-7.10.1/
./bin/elasticsearch
# Then, go to project directory and start php server
php -S localhost:port
```
where port is your php server port (such as 8888).

Then open your browser and go to `localhost:port` (such as `localhost:8888`).

### Create index

...

### Update (or create) document of index

...

### Search

...

### Delete document

...

### Delete index

...
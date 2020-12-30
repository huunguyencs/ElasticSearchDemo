# ElasticSearchDemo
Elasticsearch Demo - Database Seminar - HCMUT (2020-2021)

## Install
Install [elasticsearch](https://www.elastic.co/guide/en/elasticsearch/reference/current/install-elasticsearch.html) and [logstash](https://www.elastic.co/guide/en/logstash/current/installing-logstash.html) and [Nodejs](https://nodejs.org/en/download/package-manager/)

Install nodejs package
```bash
npm install express
npm install path
npm install pug
npm install body-parser
npm install @elastic/elasticsearch
```

## Add data to elastic search

In datasource/logstash.conf, edit `path` field and `index` field with index name.
### Example
With `bundesliga`, edit path to `bundesliga1920.csv` and index `bundesliga1920`.

Then, open terminal, start logstash and add data to elasticsearch
```bash
sudo systemctl start logstash
cd /usr/share/logstash/bin
sudo ./logstash -f *path to above conf file (logstash.conf)*
```

## Run

Open terminal,go to project folder and run
```bash
node index.js
```
Then open your browser and go to `http://localhost:8888`

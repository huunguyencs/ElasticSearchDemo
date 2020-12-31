# ElasticSearchDemo
Elasticsearch Demo - Database Seminar - HCMUT (2020-2021)

## Install

Install [Nodejs](https://nodejs.org/en/download/) and [Elasticsearch](https://www.elastic.co/downloads/elasticsearch)

Install package for nodejs:
```bash
npm install express
npm install path
npm install body-parser
npm install pug
npm install @elastic/elasticsearch
```
## Data preparation

First, run elasticsearch, then go to project folder and run:
```bash
curl -s -H "Content-Type: application/x-ndjson" -XPOST localhost:9200/_bulk?pretty --data-binary "@data.json"
```
## Run
Alter insert data to elasticsearch successfully, run:
```bash
node index.js
```
Then open your browser and go to `localhost:8888`

## Feature

1. Search by keyword:

    Elastic search will score result by your keyword. The item result, which is more closely with your keyword, will be ordered first.
2. Search by price range
3. Sort
    - Sort by closely with your keyword.
    - Sort by popularity of item.
    - Sort by price.
const port = "8888";


// set up
const express = require('express');
const path = require("path");
const bodyParser=require("body-parser");

const app = express();
app.set('view engine','pug');
app.set('views', path.join(__dirname, './public/views'));
const urlencodedParser = bodyParser.urlencoded({ extended: false });
app.use(express.static(path.join(__dirname, './public/')));


// // connect elastic search
const { Client } = require('@elastic/elasticsearch');

const client = new Client({
  node: 'http://localhost:9200',
  auth: {
    apiKey: 'base64EncodedKey'
  }
});


app.get('/',(req,res)=>{
    res.render('index');
});

app.get('/index',(req,res)=>{
    res.redirect('/');
});


app.post('/search',urlencodedParser,(req,res)=>{
    let search = req.body.search;
    let sort = req.body.sort;
    let fromprice = req.body.fromprice
    let toprice = req.body.toprice;
    
    
    let searchWord = {
        bool : {
            should : [
                {
                    match : {
                        name : search
                    }
                },
                {
                    match : {
                        brand: search
                    }
                },
                {
                    match : {
                        description : search
                    }
                },
                {
                    match : {
                        type : search
                    }
                },
                {
                    match : {
                        categories : search 
                    }
                }
            ]
        }
    }

    let searchPrice = {
        price  : {
            gte: fromprice,
            lte: toprice
        }
    }

    let sortby;
    if (sort == '_score') {
        sortby = [
            {
                _score : {
                    order : 'desc'
                }
            }
        ]
    }
    else if (sort == 'popularity'){
        sortby = [
            {
                popularity : 'desc'
            }
        ]
    }
    else{
        sortby = [
            {
                price : 'desc'
            }
        ]
    }
    sort = sort=='_score'?'relation keyword':sort;
    if (search || (toprice && fromprice)) {
        if (search && toprice && fromprice){
            client.search({
                index: 'camera',
                body: {
                    query : {
                        bool :{
                            must : [
                                searchWord,
                                {
                                    range :searchPrice
                                }
                            ]
                        }
                    },
                    sort : sortby                    
                },
                size: 2500
            }, (err, result) => {
                if (err) throw err;
                let list = result.body.hits;
                let count = list.total.value;
                res.render('result',{list:list.hits,count:count,search:search,fromprice:fromprice,toprice:toprice,sort:sort});
            })
        }
        else if (search) {
            client.search({
                index: 'camera',
                body: {
                    query : searchWord,
                    sort : sortby                    
                },
                size: 2500
            }, (err, result) => {
                if (err) throw err;
                let list = result.body.hits;
                let count = list.total.value;
                res.render('result',{list:list.hits,count:count,search:search,sort:sort});
            })
        }
        else {
            client.search({
                index: 'camera',
                body: {
                    query : {
                        range :searchPrice
                    },
                    sort : sortby                    
                },
                size: 2500
            }, (err, result) => {
                if (err) throw err;
                let list = result.body.hits;
                let count = list.total.value;
                res.render('result',{list:list.hits,count:count,fromprice,toprice,sort:sort});
            })
        }
    }
    else{
        res.redirect('/');
    }
    
})

app.listen(port,()=>{
    console.log('Running at http://localhost:' + port);
});



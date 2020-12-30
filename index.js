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

// client.search({
//     index: 'bundesliga1920',
//     body: {
//         query: {
//             match: { "AwayTeam" : "Dortmund" }
//         }
//     }
// }, (err, result) => {
//     if (err) console.log(err)
//     else console.log(result["body"]["hits"]["hits"][1]["_source"]["HomeTeam"]);
// })

app.get('/',(req,res)=>{
    res.render('index');
});

app.get('/index',(req,res)=>{
    res.redirect('/');
});

app.get('/premier',(req,res)=>{
    res.render('search',{page: 'Premier league',img:'premier'});
});

app.get('/bundesliga',(req,res)=>{
    res.render('search',{page: 'Bundesliga',img:'bundesliga'});
});

app.get('/la-liga',(req,res)=>{
    res.render('search',{page: 'La liga',img:'la-liga'});
});

app.get('/ligue-1',(req,res)=>{
    res.render('search',{page: 'Ligue 1',img:'ligue-1'});
});

app.get('/serie-a',(req,res)=>{
    res.render('search',{page: 'Serie A',img:'serie-a'});
});

app.post('/search',urlencodedParser,(req,res)=>{
    let page = req.body.page;
    let img = req.body.img;
    let team = req.body.team;
    let fromdate = req.body.fromdate;
    if (fromdate) var fromDate = dateConvert(fromdate);
    let todate = req.body.todate;
    if (todate) var toDate = dateConvert(todate);
    if (team || (fromdate && todate)) {
        if (team && fromdate && todate){
            client.search({
                index: img,
                body: {
                    query: {
                        bool : {
                            must : [
                                {
                                    bool : {
                                        should : [
                                            {
                                                match : {
                                                    HomeTeam : team
                                                }
                                            },
                                            {
                                                match : {
                                                    AwayTeam : team
                                                }
                                            }
                                        ]
                                    }
                                },
                                {
                                    range : {
                                        Date : {
                                            gte : fromDate,
                                            lte : toDate
                                        }
                                    }
                                }
                            ]
                        }                     
                    },
                    sort : [
                        {
                            Date : {
                                order : 'asc'
                            }
                        }
                    ]
                },
                size: 100
            },(err,result)=>{
                if (err) throw err;
                list = result.body.hits.hits;
                res.render('result',{page:page,img:img,list:list,team:team,fromdate:fromdate,todate:todate});
            })
        }
        else if (team){
            client.search({
                index: img,
                body: {
                    query: {
                        bool : {
                            should : [
                                {
                                    match: {
                                        HomeTeam : team
                                    }
                                },
                                {
                                    match: {
                                        AwayTeam: team
                                    }
                                }
                            ]
                        }
                    }
                },
                size: 38
            },(err,result)=>{
                if (err) throw err;
                list = result.body.hits.hits;
                res.render('result',{page:page,img:img,list:list,team:team});
            })
        }
        else {
            client.search({
                index: img,
                body: {
                    query : {
                        range : {
                            Date : {
                                gte : fromDate,
                                lte : toDate
                            }
                        }
                    },
                    sort : [
                        {
                            Date : {
                                order : 'asc'
                            }
                        }
                    ]
                },
                size: 50
            },(err,result)=>{
                if (err) throw err;
                list = result['body']['hits']['hits'];
                res.render('result',{page:page,img:img,list:list,fromdate:fromdate,todate:todate})
            })
        }
    }
    else{
        res.render('search',{page:page,img:img});
    }
});

app.post('/statistic',urlencodedParser,(req,res)=>{
    let page = req.body.page;
    
    
})

app.listen(port,()=>{
    console.log('Running at http://localhost:' + port);
});



function dateConvert(date){
    d =  date.substr(2,2) + date.substr(5,2) + date.substr(8,2);
    return parseInt(d);
}
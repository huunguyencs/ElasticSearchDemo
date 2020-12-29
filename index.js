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
// const { Client } = require('@elastic/elasticsearch');

// const client = new Client({
//   node: 'http://localhost:9200',
//   auth: {
//     apiKey: 'base64EncodedKey'
//   }
// });

// client.search({
//     index: 'bundesliga1920',
//     body: {
//         query: {
//             match: { "AwayTeam" : "Dortmund" }
//         }
//     }
// }, (err, result) => {
//     if (err) console.log(err)
//     else console.log(result["body"]["hits"]["hits"][0]["_source"]["AwayTeam"]);
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
    let fromdate = req.body.fromdate;
    console.log(fromdate);
    res.render('result',{page:page,img:img});
});

app.post('/statistic',urlencodedParser,(req,res)=>{
    let page = req.body.page;
    let team = req.body.team;
    let img = req.body.img;
    res.render('statistic',{page:page,team:team,img:img});
})

app.listen(port,()=>{
    console.log('Running at http://localhost:' + port);
});


function search(index, param){

}
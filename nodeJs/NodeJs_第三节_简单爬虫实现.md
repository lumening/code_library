#  第三节 简单NodeJs爬虫实现

## 知识点
1. 学习使用 superagent 抓取网页
2. 学习使用 cheerio 分析网页

## 安装依赖
需要用到三个依赖，分别是 express，superagent 和 
1. superagent(http://visionmedia.github.io/superagent/ ) 是个 http 方面的库，可以发起 get 或 post 请求。
2. cheerio(https://github.com/cheeriojs/cheerio ) 大家可以理解成一个 Node.js 版的 jquery，用来从网页中以 css selector 取数据，使用方式跟 jquery 一样一样的。
```
$ npm install superagent  --save
$ npm install cheerio  --save
```


## 代码
```
//引入依赖
var express = require('express');
var superagent = require('superagent');
var cheerio = require('cheerio');

var app = express();

app.get('/', function(req, res){
	//用 superagent 去抓取 https://cnodejs.org/ 的内容
	superagent.get('https://cnodejs.org/').end(function(err, sres){
		//常规的错误处理
		if(err){
			return next(err);
		}

		// sres.text 里面存储着网页的 html 内容，将它传给 cheerio.load 之后
      	// 就可以得到一个实现了 jquery 接口的变量，我们习惯性地将它命名为 `$`
      	// 剩下就都是 jquery 的内容了
      	var $ = cheerio.load(sres.text);
      	var items = [];
      	$('#topic_list .topic_title').each(function (idx, element) {
	        var $element = $(element);
	        items.push({
	          title: $element.attr('title'),
	          href: $element.attr('href')
	        });
      	});

      	res.send(items);
	})
});


//监听端口
app.listen(3000, function(req,res){
	console.log('app is running at port 3000');
});
```

## 运行结果
```
[
    {
        "title": "NodeParty@北京 #16期总结",
        "href": "/topic/57e8dca7a61dacb35502be75"
    },
    {
        "title": "分享我用cnode社区api做微信小应用的入门过程",
        "href": "/topic/57ea257b3670ca3f44c5beb6"
    },
    {
        "title": "JSConf 中国2016 PPT 分享汇总与发票问题",
        "href": "/topic/57cf84508624502e475135ef"
    }
]

```

## 扩展应用_<使用 eventproxy 控制并发>
强烈推荐 @朴灵 的 《九浅一深Node.js》 http://book.douban.com/subject/25768396/ 。

需要用到三个库：superagent cheerio eventproxy(https://github.com/JacksonTian/eventproxy )

```
var eventproxy = require('eventproxy');
var cheerio = require('cheerio');
var superagent = require('superagent');

var url = require('url');
var cnodeUrl = 'https://cnodejs.org/';
superagent.get(cnodeUrl).end(function(err, res){
	if(err){
		return console.error(err);
	}

	var topicUrls = [];
	var $ = cheerio.load(res.text);
	// 获取首页所有的链接
    $('#topic_list .topic_title').each(function (idx, element) {
      var $element = $(element);
      // $element.attr('href') 本来的样子是 /topic/542acd7d5d28233425538b04
      // 我们用 url.resolve 来自动推断出完整 url，变成
      // https://cnodejs.org/topic/542acd7d5d28233425538b04 的形式
      // 具体请看 http://nodejs.org/api/url.html#url_url_resolve_from_to 的示例
      var href = url.resolve(cnodeUrl, $element.attr('href'));
      topicUrls.push(href);
    });

    var ep = new eventproxy();
    ep.after('topic_html', topicUrls.length, function (topics) {
      topics = topics.map(function (topicPair) {
        var topicUrl = topicPair[0];
        var topicHtml = topicPair[1];
        var $ = cheerio.load(topicHtml);
        return ({
          title: $('.topic_full_title').text().trim(),
          href: topicUrl,
          comment1: $('.reply_content').eq(0).text().trim(),
        });
      });

      console.log('final:');
      console.log(topics);
    });

    topicUrls.forEach(function (topicUrl) {
      superagent.get(topicUrl)
        .end(function (err, res) {
          console.log('fetch ' + topicUrl + ' successful');
          ep.emit('topic_html', [topicUrl, res.text]);
        });
    });

});
```

`ep.all('data1_event', 'data2_event', 'data3_event', function (data1, data2, data3) {});`

这一句，监听了三个事件，分别是 data1_event, data2_event, data3_event，每次当一个源的数据抓取完成时，就通过 ep.emit() 来告诉 ep 自己，某某事件已经完成了。

当三个事件未同时完成时，ep.emit() 调用之后不会做任何事；当三个事件都完成的时候，就会调用末尾的那个回调函数，来对它们进行统一处理。

eventproxy 提供了不少其他场景所需的 API，但最最常用的用法就是以上的这种，即：

先 `var ep = new eventproxy();` 得到一个 eventproxy 实例。
告诉它你要监听哪些事件，并给它一个回调函数。`ep.all('event1', 'event2', function (result1, result2) {})`。
在适当的时候 `ep.emit('event_name', eventData)`。





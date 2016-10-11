# 第二节 使用外面模块


## 知识点
1. 学习 req.query 的用法
2. 学习建立 package.json 来管理 Node.js 项目。

## 新建一个Node项目
```
$ mkdir NodePoj_2 && cd NodePoj_2

$ npm init
//这个命令的作用就是帮我们互动式地生成一份最简单的 package.json 文件

$  npm install express utility --save
//--save 参数，这个参数的作用，就是会在你安装依赖的同时，自动把这些依赖写入 package.json

```

## 项目代码
建立一个 app.js 文件
```
//引入依赖
var express = require('express');
var utility = require('utility');

var app = express();

app.get('/', function(req, res){
	// 如果是 post 传来的 body 数据，则是在 req.body 里面，不过 express 默认不处理 body 中的信息，
	// 需要引入 https://github.com/expressjs/body-parser 这个中间件才会处理，这个后面会讲到。
    // 如果分不清什么是 query，什么是 body 的话，那就需要补一下 http 的知识了
	var  query = req.query;


	// 调用 utility.md5 方法，得到 md5 之后的值
  	// 之所以使用 utility 这个库来生成 md5 值，其实只是习惯问题。每个人都有自己习惯的技术堆栈，
  	// utility 的 github 地址：https://github.com/node-modules/utility
	var md5Value = utility.md5(query.q);
	res.send(md5Value);
});


//监听端口
app.listen(3000, function(req,res){
	console.log('app is running at port 3000');
});
```

## 运行
```
$ node app.js
//访问 http://localhost:3000/?q=helloword，完成。
```

### 附:
```
TypeError: Not a string or buffer
   at TypeError (native)
   at Hash.update (crypto.js:70:16)
   at Object.hash (/data/vhosts/testProj/node_lesson/node_modules/utility/lib/crypto.js:31:7)
   at Object.md5 (/data/vhosts/testProj/node_lesson/node_modules/utility/lib/crypto.js:44:18)
   at /data/vhosts/testProj/node_lesson/app.js:15:25
   at Layer.handle [as handle_request] (/data/vhosts/testProj/node_lesson/node_modules/express/lib/router/layer.js:95:5)
   at next (/data/vhosts/testProj/node_lesson/node_modules/express/lib/router/route.js:131:13)
   at Route.dispatch (/data/vhosts/testProj/node_lesson/node_modules/express/lib/router/route.js:112:3)
   at Layer.handle [as handle_request] (/data/vhosts/testProj/node_lesson/node_modules/express/lib/router/layer.js:95:5)
   at /data/vhosts/testProj/node_lesson/node_modules/express/lib/router/index.js:277:22
```
这个错误是从 crypto.js 中抛出的。
这是因为，当我们不传入 q 参数时，req.query.q 取到的值是 undefined，utility.md5 直接使用了这个空值，导致下层的 crypto 抛错。



    /**
     * cookie处理类
     * @type Object  --注意替换domain
     */
    var Cookie = {
        set: function(name, value, opt){
            opt || (opt = {});
            var t = new Date(), exp = opt.exp;
            if(typeof exp==='number'){
                t.setTime(t.getTime() + exp*3600000); //60m * 60s * 1000ms
            }else if(exp==='forever'){
                t.setFullYear(t.getFullYear()+50); //专业种植cookie 50年
            }else if(value===null){ //删除cookie
                value = '';
                t.setTime(t.getTime() - 3600000);
            }else if(exp instanceof Date){ //传的是一个时间对象
                t = exp;
            }else{
                t = '';
            }
            document.cookie = name+'='+encodeURIComponent(value)+(t && '; expires='+t.toUTCString())+
                '; domain='+(opt.domain || '.xx.com')+'; path='+(opt.path || '/')+(opt.secure ? '; secure' : '');
        },
        get: function(name){
            name += '=';
            var cookies = (document.cookie || '').split(';'),
                cookie,
                nameLength = name.length,
                i = cookies.length;
            while(i--){
                cookie = cookies[i].replace(/^\s+/,'');
                if(cookie.slice(0,nameLength)===name){
                    return decodeURIComponent(cookie.slice(nameLength)).replace(/\s+$/,'');
                }
            }
            return '';
        }
    };

    
    /**
     * 封装Cookie方法
     * @param  string name
     * @param  string value
     * @param  object options
     * @return string
     * 
     */
    function cookieTool(name, value, options) {
        if(typeof value==='undefined'){
            return Cookie.get(name);
        }
        if(options){
            options.exp = typeof options.expires==='number' ? options.expires * 24 :
            options.expires; //原来的cookie是按天算的
        }
        Cookie.set(name, value, options);
    }

    //获取GET参数
    function getQuery(key, url) {
        var urlArgs = url || location.search;
        if (urlArgs.length > 0 && urlArgs.indexOf("?") != -1) {
            var regex = new RegExp(key + "=([^&]*)", "i"),
                result = urlArgs.match(regex);
            return (result && result.length > 0) ? unescape(result[1]) : null;
        }
        return null;
    }

    //example
    function handler(data){
        if(data && data.vast){
            var cuid = data.vast.cuid;
            var le_cookie = cookieTool('ark_uuid');
            if(location.host.indexOf('.le.com') >= 0 && cuid && (!le_cookie || le_cookie != cuid)){
                cookieTool('ark_uuid',cuid,{
                    'expires':new Date(2060,00,01, 08,00,01),
                    'domain':'.le.com'
                });
                //广告用户cookie设置到le.com成功后发送检测
                var srcType = ''; 
                var purl = getQuery('purl',location.href) || '';
                if(purl.indexOf('.lesports.com') > 0) 
                    srcType = 'http://ark.letv.com/apsdbg/cm/sport?url=';
                else
                    srcType = 'http://ark.letv.com/apsdbg/cm/cloud?url=';

                var img = document.createElement("img");
                img.src = srcType + encodeURIComponent(purl);
                img.onload = function(){
                    img = null;
                }
            }
        }
    };
    var url = 'http://ark.letv.com/s?ark=0&ct=0&n=0&res=jsonp&j=handler&callback=handler';
    var script = document.createElement('script');
    script.setAttribute('src', url);
    document.getElementsByTagName('head')[0].appendChild(script); 

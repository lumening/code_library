
/**
 *  事件处理工具
 * @type Object
 */
Tool.Event = {
    add : function(elm, evType, fn, useCapture){
        useCapture = (useCapture) ? true : false;
        if (elm.addEventListener) {
            elm.addEventListener(evType, fn, useCapture);
            return true;
        }else if (elm.attachEvent) {
            var r = elm.attachEvent('on' + evType, fn);
            return r;
        }else {
            elm['on' + evType] = fn;
        }
    },
    remove : function(elm,evType,fn){
        if (elm.detachEvent){
            elm.detachEvent('on'+evType,fn);
        }else if (elm.removeEventListener){
            elm.removeEventListener(evType, fn, false);
        }else{
            elm['on' + evType] = null;
        }
    }
};



var MiniSite = MiniSite || {};
/**
 * 根据UserAgent检测浏览器类型
 *
 * @method module:minisite.Browser
 */
MiniSite.Browser = MiniSite.Browser || {
    ie: /msie/.test(window.navigator.userAgent.toLowerCase()),
    moz: /gecko/.test(window.navigator.userAgent.toLowerCase()),
    opera: /opera/.test(window.navigator.userAgent.toLowerCase()),
    safari: /safari/.test(window.navigator.userAgent.toLowerCase())
};

/**
 * JSONP跨域取数据方法
 *
 * @method module:minisite.loadJSData
 * @param {String} sUrl
 * @param {String} sCharset
 * @param {Function} fCallback
 */
MiniSite.loadJSData = MiniSite.loadJSData || function( sUrl, sCharset, fCallback ){
    var _script = document.createElement('script');
    _script.setAttribute('charset', sCharset);
    _script.setAttribute('type', 'text/javascript');
    _script.setAttribute('src', sUrl);
    document.getElementsByTagName('head')[0].appendChild(_script);
    if(MiniSite.Browser.ie){
        _script.onreadystatechange = function(){
            if(this.readyState === 'loaded' || this.readyState === 'complete'){
                setTimeout(function(){
                    try{
                        fCallback();
                    }catch(e){}
                }, 50);
            }
        };
    }else if(MiniSite.Browser.moz){
        _script.onload = function(){
            setTimeout(function(){
                try{
                    fCallback();
                }catch(e){}
            }, 50);
        };
    }else{
        setTimeout(function(){
            try{
                fCallback();
            }catch(e){}
        }, 50);
    }
};



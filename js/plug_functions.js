//chrome,FireFox,Opera,safria是否有插件
function hasPlugin(name) {
    name = name.toLowerCase();
    for (var i = 0; i < navigator.plugins.length; i++) {
        if(navigator.plugins[i].name.toLowerCase().indexOf(name)>-1){
            return true;
        }
    };
    return false;
}

//IE是否有此插件
function hasIEPlgin(name){
    try{
        new ActiveXObject(name);
        return true;
    }catch(ex){
        return false;
    }
}

//是否安装flash
function hasFlash(){
    var result = hasPlugin('flash');
    if(!result){
        result = hasIEPlugin('ShockwaveFlash.ShockwaveFlash');
    }
    return result;
}
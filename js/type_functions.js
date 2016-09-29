var Util = Util || {};
//判断主要原理;
//1.对于基本属性使用typeof 检查
//2.对于复合对象类型使用Object.prototype.toString.call(obj)来检查，例如数组类型会返回 [object Array]
Util.type = function(obj){
    var class2type = {};
    var types = "Boolean Number String Function Array Date RegExp Object Error".split(' ');
    if ( obj == null) {
        return obj + "";
    }
    for (var i = 0; i < types.length; i++) {
        class2type[ "[object " + types[i] + "]" ] = types[i].toLowerCase();
    };
    return typeof obj === "object" || typeof obj === "function" ?
    class2type[ Object.prototype.toString.call(obj) ] || "object" :
        typeof obj;
};

//Douglas Crockford 的填鸭式方法
Util.isArray2 = function(value){
    return value &&
    typeof value === 'object' &&
    typeof value.length === 'number' &&
    typeof value.splice === 'function' &&
    !(value.propertyIsEnumerable('length'));
};
//jquery使用的方法
Util.isArray = function(obj){
    return this.type(obj) === 'array';
};
Util.isFunction = function(obj){
    return this.type(obj) === 'function';
};

Util.isObject = function(obj){
    return this.type(obj) === 'object';
};

Util.isNumber = function(obj){
    return this.type(obj) === 'number';
};

Util.isBoolean = function(obj){
    return this.type(obj) === 'boolean';
};

Util.isString = function(obj){
    return this.type(obj) === 'string';
};

Util.isNull = function(obj){
    return Object.prototype.toString.call(obj) === '[object Null]';
};

Util.isNumeric = function(obj){
    return !Object.prototype.toString.call(obj) !== '[object Array]' &&
    (obj - parseFloat( obj ) + 1) >= 0;
};

Util.isEmptyObject = function( obj ) {
    var name;
    for ( name in obj ) {
        return false;
    }
    return true;
};


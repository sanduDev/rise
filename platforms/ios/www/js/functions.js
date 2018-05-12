function str_replace(value, input, output){
        return (!value) ? '' : value.replace(input, output);
}

function valuesToArray(obj) {
    return Object.keys(obj).map(function (key) {
        return obj[key];
    });
}

function keysFromObject(obj){
    var keys = [];
    for(var k in obj){
        keys.push(k);
    }
    return keys;
}

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function inArray(needle, haystack) {
//    inArray(element, array);
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] === needle){
            return true;
        }
    }
    return false;
}

function objIsEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}

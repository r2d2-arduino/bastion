function checkNav(obj)
{
    $(obj).parent().parent().children('.nav-item').removeClass('active');
    $(obj).parent().addClass('active');
    
    var name = $(obj).data('name');
    var id = $(obj).data('id');
    
    console.log(name + ':' + id);
    
    return false;
}

function getParam(name)
{
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results ? (results[1] || 0) : null;
}

function insertParam(key, value)
{
    key = encodeURIComponent(key); 
    value = encodeURIComponent(value);

    var s = document.location.search;
    var kvp = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

    s = s.replace(r,"$1"+kvp);

    if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};

    //again, do what you will here
    document.location.search = s;
}
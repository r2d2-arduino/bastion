function checkNav(obj)
{
    $(obj).parent().parent().children('.nav-item').removeClass('active');
    $(obj).parent().addClass('active');
    
    var name = $(obj).data('name');
    var id = $(obj).data('id');
    
    console.log(name + ':' + id);
    //changeParam(name, id);
    
    if (name === 'home_id')
    {
        window.location.href = window.location.origin + '?home_id=' + id;
    }
    if (name === 'position_id')
    {
        let home_id = getParam('home_id');
        window.location.href = window.location.origin + '?home_id=' + home_id + '&position_id=' + id;
    }
    if (name === 'device_id')
    {
        let home_id = getParam('home_id');
        let position_id = getParam('position_id');
        window.location.href = window.location.origin + '?home_id=' + home_id + '&position_id=' + position_id + '&device_id=' + id;
    }
    if (name === 'type_id')
    {
        changeParam(name, id);
    }
    
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

function changeParam(key, value)
{
    if (!getParam(key))
    {
        let amp  = '?';
        
        if (window.location.href.indexOf('?') > -1)
        {
            amp = '&';
        }
        window.location.href = window.location.href + amp + key + '=' + value
    }
    else
    {
        insertParam(key, value);
    }
    return false;
}
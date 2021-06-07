function checkNav(obj)
{
    $(obj).parent().parent().children('.nav-item').removeClass('active');
    $(obj).parent().addClass('active');
    
    var name = $(obj).data('name');
    var id = $(obj).data('id');
    
    console.log(name + ':' + id);
    
    return false;
}

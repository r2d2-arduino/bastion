var _sensorGetCount = 0;
function getLastSensorsValue(sensor_id = 0, device_id = 0)
{
    if ($('.btn.logout').length === 0 || _sensorGetCount > 1000 || $('#checkUpdate:checked').length === 0)
    {
        return false;
    }
    _sensorGetCount++;
        
    $('.speedometer').removeClass('alive');
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: '/sensor/actual',
        data: { sensor_id: sensor_id, device_id: device_id },
        success: function (data) 
        {
            var value = 0;
            if (data.length === 0)
            {
                return;
            }
            var min = 0;
            var max = 0;
            var newGrad = 0;
            var oldGrad = 0;
            
            for (var i = 0; i < data.length; i++)
            {
                if (data[i].actuality < 100)
                {
                    var name = '#sensor_'+data[i].device_id+'_'+data[i].sensor_id;
                    //console.log(name);
                    min = $(name).data('min');
                    max = $(name).data('max');
                    oldGrad = $(name).data('grad');

                    //console.log(data[i]);
                    value = data[i].value;
                    if (value > 100)
                    {
                        value = Math.round(value);
                    }
                    else
                    {
                        value = Math.round(value * 10) / 10;
                    }

                    newGrad = Math.round(180 * (value - min) / (max - min));

                    $(name).data('grad', newGrad);
                    $(name +' .number').html(value);
                    $(name).parent().addClass('alive');
                    animateNeedleRotate(name, oldGrad, newGrad);    
                }
            }
        },
        error: function (exception) {
            alert('Error in getLastSensorsValue');
            console.log(exception);
        }
    });
}

function animateNeedleRotate(name, oldGrad, newGrad)
{
    var $needle = $(name +' .needle');
    //console.log(oldGrad + '>' + newGrad);
    $({deg: oldGrad}).animate({deg: newGrad}, {
        duration: 1000,
        step: function(now) {
            $needle.css({
                transform: 'rotate(' + now + 'deg)'
            });
        }
    });
}
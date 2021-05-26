function getLastSensorsValue(sensor_id = 0)
{
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'index.php?r=sensor-value%2Factual',
        data: { sensor_id: sensor_id },
        success: function (data) 
        {
            var value = 0;
            if (data.length === 0)
            {
                return;
            }
            var min = 0;
            var max = 0;
            var grad = 0;
            var gradO = 0;
            
            for (var i = 0; i < data.length; i++)
            {
                min = $('#sensor_'+data[i].sensor_id).data('min');
                max = $('#sensor_'+data[i].sensor_id).data('max');
                gradO = $('#sensor_'+data[i].sensor_id).data('grad');
            
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
                
                grad = Math.round(180 * (value - min) / (max - min));
                
                $('#sensor_'+data[i].sensor_id +' .number').html(value);
                animateNeedleRotate(data[i].sensor_id, gradO, grad);    
            }
        },
        error: function (exception) {
            alert('Error in getLastSensorsValue');
            console.log(exception);
        }
    });
}

function animateNeedleRotate(sensor_id, oldGrad, newGrad)
{
    console.log('animate ' + sensor_id);
    var $needle = $('#sensor_'+sensor_id +' .needle');
    
    $({deg: oldGrad}).animate({deg: newGrad}, {
        duration: 1000,
        step: function(now) {
            $needle.css({
                transform: 'rotate(' + now + 'deg)'
            });
        }
    });
}
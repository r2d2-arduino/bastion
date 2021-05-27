<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\SensorValue;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GateController extends Controller
{
    const CHANNEL = 1;

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }
    
    public function actionValue($raw = '')
    {
        //yii gate/value 2;2;20.43
        
        $parts = explode(';', $raw);
        
        if (count($parts) === 3)
        {           
            echo SensorValue::add($parts[0], $parts[1], $parts[2])."\n";
        }        
        else
        {
            echo "ERROR: Incorrect data size!\n";
        }      

        return ExitCode::OK;
    }
    
    public function actionData($raw = '')
    {
        //yii gate/data 2;2;20.43;...
        
        $error = self::processingData($raw);
        
        if ($error)
        {
            echo $error."\n";
        }
        
        return ExitCode::OK;
    }
    
    private static function processingData($raw)
    {
        $parts = explode(';', $raw);
        
        if (count($parts) !== 9)
        {
            return "ERROR: Incorrect data size!";
        }
        if ((int) $parts[0] !== self::CHANNEL)
        {
            return "ERROR: Incorrect channel";
        }        
        if (!self::checkCRC($parts))
        {
            return "ERROR: Bad CRC sum!";
        }
        
        $channel_id = (int) $parts[0];
        $device_id  = (int) $parts[1];
        $sensor_id  = (int) $parts[2];
        $valueH     = (int) $parts[3];
        $valueL     = (int) $parts[4];
        $type       = (int) $parts[5];
        $counter    = (int) $parts[6];
        $crc1       = (int) $parts[7];
        $crc2       = (int) $parts[8];
        
        $value = self::getDataValue($valueH, $valueL, $type);
        
        return SensorValue::add($device_id, $sensor_id, $value);
    }
    
    private static function getDataValue($valueH, $valueL, $type)
    {
        if ($type === 2)
        {
            return $valueH * 256 + $valueL;
        }
        return $valueH + ($valueL / 100);
    }
    
    private static function checkCRC($parts)
    {
        $total = (int) $parts[0] + (int) $parts[1] + (int) $parts[2] + (int) $parts[3] + (int) $parts[4] + (int) $parts[5] + (int) $parts[6];
        
        return ( ( (int) $parts[7] === (int) floor($total / 256)) && ( (int) $parts[8] === (int) floor($total % 256)) );
    }
    
}

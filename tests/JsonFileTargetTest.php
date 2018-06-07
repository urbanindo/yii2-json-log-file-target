<?php
/**
 * Class JsonLogFileTargetTest
 * @author Petra Barus <petra@urbanindo.com>
 */

use UrbanIndo\Yii2\JsonFileTarget\JsonFileTarget;
use yii\log\Logger;
use yii\helpers\Json;

class JsonFileTargetTest extends PHPUnit_Framework_TestCase
{

    public function testFormatMessage() {
        $fileTarget = new JsonFileTarget();
        $text = 'message';
        $level = Logger::LEVEL_INFO;
        $category = 'application';
        $timestamp = 1508160390.6083;
        $message = [$text, $level, $category, $timestamp];
        $formatted = $fileTarget->formatMessage($message);
        $expected = <<<JSON
{"timestamp":"2017-10-16 13:26:30","level":"info","category":"application","traces":[],"message":"message","ip":"-","userId":"-","sessionId":"-","context":null}
JSON
        ;
        $this->assertEquals($expected, $formatted);
    }

    public function testFormatMessageWithException() {
        $fileTarget = new JsonFileTarget();
        $text = new Exception("TEST");
        $level = Logger::LEVEL_INFO;
        $category = 'application';
        $timestamp = 1508160390.6083;
        $message = [$text, $level, $category, $timestamp];
        $message['context'] = ['1' => '1'];
        $formatted = $fileTarget->formatMessage($message);

        $json = Json::decode($formatted);
        $this->assertNotEmpty($json['traces']);
        $this->assertEquals($json['context'], ['1' => '1']);
    }
}
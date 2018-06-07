<?php
/**
 * Class JsonLogFileTargetTest
 * @author Petra Barus <petra@urbanindo.com>
 */

use UrbanIndo\Yii2\JsonFileTarget\JsonFileTarget;
use yii\helpers\FileHelper;
use yii\log\Logger;
use yii\helpers\Json;
use yii\log\Dispatcher;

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

    public function testLog() {
        $dir = dirname(Yii::$app->basePath);
        $logFile = $dir . '/log/test.log';
        FileHelper::removeDirectory(dirname($logFile));
        mkdir(dirname($logFile), 0777, true);
        $logger = new Logger();

        new Dispatcher([
            'logger' => $logger,
            'targets' => [
                'file' => [
                    'class' => JsonFileTarget::class,
                    'logFile' => $logFile,
                    'levels' => ['warning'],
                    'maxFileSize' => 1024, // 1 MB
                    'maxLogFiles' => 1, // one file for rotation and one normal log file
                    'logVars' => [],
                ],
            ],
        ]);

        $logger->log(str_repeat('x', 1024), Logger::LEVEL_WARNING);
        $logger->flush(true);

        $this->assertFileExists($logFile);
        $this->assertFileNotExists($logFile . '.1');
        $this->assertFileNotExists($logFile . '.2');
        $this->assertFileNotExists($logFile . '.3');
        $this->assertFileNotExists($logFile . '.4');
    }
}
<?php

namespace Mix\Log;

use Mix\Helpers\JsonHelper;
use Mix\Core\Component;
use Mix\Core\ComponentInterface;

/**
 * Log组件
 * @author 刘健 <coder.liu@qq.com>
 */
class Log extends Component
{

    // 协程模式
    public static $coroutineMode = ComponentInterface::COROUTINE_MODE_REFERENCE;

    // 轮转规则
    const ROTATE_HOUR = 0;
    const ROTATE_DAY = 1;
    const ROTATE_WEEKLY = 2;

    // 日志目录
    public $dir = 'logs';

    // 日志记录级别
    public $level = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

    // 日志轮转类型
    public $rotate = self::ROTATE_DAY;

    // 最大文件尺寸
    public $maxFileSize = 0;

    // emergency日志
    public function emergency($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    // alert日志
    public function alert($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    // critical日志
    public function critical($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    // error日志
    public function error($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    // warning日志
    public function warning($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    // notice日志
    public function notice($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    // info日志
    public function info($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    // debug日志
    public function debug($message, array $context = [])
    {
        return $this->log(__FUNCTION__, $message, $context);
    }

    // 记录日志
    public function log($level, $message, array $context = [])
    {
        if (in_array($level, $this->level)) {
            return $this->write($level, $message, $context);
        }
        return false;
    }

    // 写入日志
    public function write($filePrefix, $message, array $context = [])
    {
        $file    = $this->getFile($filePrefix);
        $message = $this->getMessage($message, $context);
        return error_log($message . PHP_EOL, 3, $file);
    }

    // 获取要写入的文件
    protected function getFile($filePrefix)
    {
        // 生成文件名
        $logDir = $this->dir;
        if (pathinfo($this->dir)['dirname'] == '.') {
            $logDir = \Mix::$app->getRuntimePath() . DIRECTORY_SEPARATOR . $this->dir;
        }
        switch ($this->rotate) {
            case self::ROTATE_HOUR:
                $subDir     = date('Ymd');
                $timeFormat = date('YmdH');
                break;
            case self::ROTATE_DAY:
                $subDir     = date('Ym');
                $timeFormat = date('Ymd');
                break;
            case self::ROTATE_WEEKLY:
                $subDir     = date('Y');
                $timeFormat = date('YW');
                break;
        }
        $filename = "{$logDir}/{$subDir}/{$filePrefix}_{$timeFormat}";
        $file     = "{$filename}.log";
        // 创建目录
        $dir = dirname($file);
        is_dir($dir) or mkdir($dir, 0777, true);
        // 尺寸轮转
        $number = 0;
        while (file_exists($file) && $this->maxFileSize > 0 && filesize($file) >= $this->maxFileSize) {
            $file = "{$filename}_" . ++$number . '.log';
        }
        // 返回
        return $file;
    }

    // 获取要写入的消息
    protected function getMessage($message, array $context = [])
    {
        // 替换占位符
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }
        $message = strtr($message, $replace);
        // 增加时间
        $time    = date('Y-m-d H:i:s');
        $message = "[time] {$time} [message] {$message}";
        return $message;
    }

}

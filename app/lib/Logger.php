<?php

namespace logger;

const _LOG_LEVEL = 'WARNING';

const _LOG_LEVELS = [
    'NOT_VIEW' => -1,
    'DEBUG' => 0,
    'INFO' => 1,
    'WARNING' => 2,
    'ERROR' => 3,
];

function _shouldLog(string $level): bool
{
    try {
        if (!array_key_exists($level, _LOG_LEVELS)) {
            throw new \InvalidArgumentException("Invalid log level: $level");
        }
    } catch (\InvalidArgumentException $e) {
        return false;
    }
    return _LOG_LEVELS[$level] >= _LOG_LEVELS[_LOG_LEVEL];
}

function _getCaller(array $backtrace): array
{
    if (count($backtrace) < 1) {
        return ['file' => 'UNKNOWN', 'line' => 'UNKNOWN'];
    }
    $caller = $backtrace[0];
    if (count($backtrace) > 1) {
        if (isset($backtrace[1]['class']) && $backtrace[1]['class'] === __NAMESPACE__) {
            $caller = $backtrace[1];
        }
    }

    return [
        'file' => $caller['file'] ?? 'UNKNOWN',
        'line' => $caller['line'] ?? 'UNKNOWN',
    ];
}

function _formatLogMessage(string $message, array $caller): string
{
    $file = $caller['file'];
    $documentRoot = $_SERVER['DOCUMENT_ROOT'];
    $file = substr($file, strlen($documentRoot));
    $line = $caller['line'];
    return "$message [File: $file, Line: $line]";
}

function _sendAsyncLog(string $level, string $message, string $tag)
{
    $data = [
        'time' => date('Y-m-d\TH:i:s\Z'),
        'tag' => $tag,
        'level' => $level,
        'text' => $message
    ];
    $jsonData = json_encode($data);

    $fp = fsockopen('217.12.40.48', 8080, $errno, $errstr, 30);
    if (!$fp) {
        echo "Ошибка: $errstr ($errno)\n";
        return;
    }

    $out = "POST /log HTTP/1.1\r\n";
    $out .= "Host: 217.12.40.48\r\n";
    $out .= "Content-Type: application/json\r\n";
    $out .= "Content-Length: " . strlen($jsonData) . "\r\n";
    $out .= "Connection: Close\r\n\r\n";
    $out .= $jsonData;

    fwrite($fp, $out);
    fclose($fp);
}

function _handleException(\Throwable $exception)
{
    $caller = [
        'file' => $exception->getFile(),
        'line' => $exception->getLine()
    ];
    $logMessage = _formatLogMessage("Uncaught exception: " . $exception->getMessage(), $caller);
    createErrorLog($logMessage, "EXCEPTION", $caller);
}

function _handleError(int $errno, string $errstr, string $errfile, int $errline)
{
    $caller = [
        'file' => $errfile,
        'line' => $errline
    ];
    $logMessage = _formatLogMessage($errstr, $caller);
    createErrorLog($logMessage, "ERROR", $caller);
    return true;
}

function createLog(string $level, string $message, string $tag = 'NO-TEG', array $caller = [])
{
    if (_shouldLog($level)) {
        if (empty($caller)) {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
            $caller = _getCaller($backtrace);
        }
        $logMessage = _formatLogMessage($message, $caller);
        _sendAsyncLog($level, $logMessage, $tag);
    }
}

function createLogFromArray($data, $tag = "")
{
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
    $caller = _getCaller($backtrace);
    createDebugLog(print_r($data, true), $tag, $caller);
}

function createDebugLog(string $message, string $tag = 'NO-TEG', array $caller = [])
{
    createLog('DEBUG', $message, $tag, $caller);
}

function createInfoLog(string $message, string $tag = 'NO-TEG', array $caller = [])
{
    createLog('INFO', $message, $tag, $caller);
}

function createWarningLog(string $message, string $tag = 'NO-TEG', array $caller = [])
{
    createLog('WARNING', $message, $tag, $caller);
}

function createErrorLog(string $message, string $tag = 'NO-TEG', array $caller = [])
{
    createLog('ERROR', $message, $tag, $caller);
}



set_error_handler(__NAMESPACE__ . '\_handleError', E_ALL);
set_exception_handler(__NAMESPACE__ . '\_handleException');

<?php

namespace Core\Models;

/**
 * Error
 * 
 * @property int $code
 * @property string $message
 * @property string $file
 * @property int $line
 * @property string $trace
 */
class Error
{
    public $code;
    public $message;
    public $file;
    public $line;
    public $trace;
}

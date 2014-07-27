<?php

class LaterPay_Logger
{

    const DEBUG     = 100;
    const INFO      = 200;
    const NOTICE    = 250;
    const WARNING   = 300;
    const ERROR     = 400;
    const CRITICAL  = 500;
    const ALERT     = 550;
    const EMERGENCY = 600;
    protected static $levels = array(
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    );

    /**
     * @var LaterPay_Logger_Abstract
     */
    protected static $_instance;
    protected static $_uniqid = null;

    /**
     * @var array
     */
    protected static $_options = array();
    protected static $_name = 'default';

    public static function init( $name, array $params ) {
        self::$_name = $name;
        if ( isset($params[$name]) ) {
            self::$_options = $params[$name];
        } else {
            self::$_options = array();
        }
    }

    public static function set_instance( $instance ) {
        self::$_instance = $instance;
    }

    public static function get_instance() {
        if ( empty(self::$_instance) ) {
            try {
                if ( defined('LATERPAY_LOGGER_ENABLED') && defined('LATERPAY_LOGGER_FILE') && LATERPAY_LOGGER_ENABLED ) {
                    self::$_instance = new LaterPay_Logger_Handler_Stream(LATERPAY_LOGGER_FILE);
                } else {
                    self::$_instance = new LaterPay_Logger_Handler_Null();
                }
            } catch ( Exception $e ) {
                self::$_instance = new LaterPay_Logger_Handler_Null();
            }
        }

        return self::$_instance;
    }

    /**
     * Adds a log record at the DEBUG level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return Boolean Whether the record has been processed
     */
    public static function debug( $message, array $context = array() ) {
        return self::log(self::DEBUG, $message, $context);
    }

    /**
     * Add a log record at the ERROR level.
     *
     * @param string $message The log message
     * @param array  $context The log context
     *
     * @return Boolean Whether the record has been processed
     */
    public static function error( $message, array $context = array() ) {
        return self::log(self::ERROR, $message, $context);
    }

    /**
    * @param integer $level
    * @param string  $message
    */
    public static function log( $level, $message, array $context = array() ) {
        if ( ! self::$_uniqid ) {
            self::$_uniqid = uniqid(getmypid() . '_');
        }
        $date = new DateTime();
        $record = array(
            'message'       => (string) $message,
            'pid'           => self::$_uniqid,
            'context'       => $context,
            'level'         => $level,
            'level_name'    => self::get_level_name($level),
            'channel'       => self::$_name,
            'datetime'      => $date,
            'extra'         => array(),
        );
        try {
            $result = self::get_instance()->handle($record);
        } catch ( Exception $e ) {
            return false;
        }

        return $result;
    }

    /**
     * Get the name of the logging level.
     *
     * @param integer $level
     *
     * @return string
     */
    public static function get_level_name( $level ) {
        return self::$levels[$level];
    }

}
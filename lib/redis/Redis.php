<?php
namespace phpbase\lib\redis;

use phpbase\lib\util\Arrays;
use phpbase\lib\log\Log;

/**
 * Class Redis
 * @package phpbase\lib\redis
 * @author qian lei
 * @method static bool|string get($key) Get the value related to the specified key
 * @method static bool|string set($key, $value, $timeout = 0) Set the string value in argument as value of the key.
 * @method static string info($option = null) Returns an associative array of strings and integers.
 */
class Redis
{
    /**
     * @var self $instance
     */
    private static $instance;

    /**
     * @var array $opts redis config
     */
    private static $opts = [];

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * @var bool
     */
    private $connected = false;

    private function __construct(){}
    private function __clone(){}

    /**
     * @param string $name
     * @param string|array $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments = null)
    {
        self::getInstance();
        try {
            self::$instance->connect();
            return self::$instance->execCommand($name, $arguments);
        } catch (\RedisException $e) {
            self::$instance->close();
            Log::error('redis', [
                'userError' => $e->getMessage(),
                'redisError' => self::$instance->redis->getLastError()
            ]);
            return false;
        }
    }

    /**
     * @return self
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param array $opts
     */
    public static function setOpts(array $opts)
    {
        self::$opts = $opts;
        if (null === Arrays::getValue($opts, 'timeout')) {
            self::$opts['timeout'] = 3;
        }
    }

    /**
     * @param string $name
     * @param string|array $arguments
     * @return mixed
     * @throws \RedisException
     */
    protected function execCommand($name, $arguments)
    {
        if (!method_exists($this->redis, $name)) {
            throw new \RedisException("Redis Function {$name} not found");
        }
        return call_user_func_array([$this->redis, $name], $arguments);
    }

    /**
     * connect redis
     * @return bool
     * @throws \RedisException
     */
    protected function connect()
    {
        if ($this->connected && is_object($this->redis)) {
            return $this->redis->ping() == '+PONG';
        }
        $this->redis = new \Redis();
        if (!$this->connected = $this->redis->connect(self::$opts['host'], self::$opts['port'], self::$opts['timeout'])) {
            throw new \RedisException('Redis connect error');
        }
        if (Arrays::getValue(self::$opts, 'auth')) {
            if (!$this->redis->auth(self::$opts['auth'])) {
                throw new \RedisException('Auth failed');
            }
        }
        if (Arrays::getValue(self::$opts, 'database')) {
            if (!$this->redis->select(self::$opts['database'])) {
                throw new \RedisException('Select database error');
            }
        }
        return true;
    }

    /**
     * close
     */
    protected function close()
    {
        if (!Arrays::getValue(self::$opts, 'pConnect') && $this->connected && is_object($this->redis)) {
            $this->connected = false;
            $this->redis->close();
            $this->redis = null;
        }
    }
}
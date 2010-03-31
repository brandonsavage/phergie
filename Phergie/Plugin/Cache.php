<?php

class Phergie_Plugin_Cache extends Phergie_Plugin_Abstract
{
    protected $cache = array();

    public function store($key, $data, $ttl = 3600, $overwrite = true)
    {
        if(!$overwrite && isset($this->cache[$key])) {
            return false;
        }

        if($ttl) {
            $expires = time()+$ttl;
        } else {
            $expires = null;
        }

        $this->cache[$key] = array('data' => $data, 'expires' => $expires);
        return true;

    }

    public function fetch($key)
    {
        if(!isset($this->cache[$key])) {
            return false;
        }

        $item = $this->cache[$key];
        if(!is_null($item['expires']) && $item['expires'] < time()) {
            $this->expire($key);
            return false;
        }

        return $item['data'];
    }

    protected function expire($key)
    {
        if(!isset($this->cache[$key])) {
            return false;
        }
        unset($this->cache[$key]);
        return true;
    }
}
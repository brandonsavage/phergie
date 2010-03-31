<?php
/**
* Phergie
*
* PHP version 5
*
* LICENSE
*
* This source file is subject to the new BSD license that is bundled
* with this package in the file LICENSE.
* It is also available through the world-wide-web at this URL:
* http://phergie.org/license
*
* @category  Phergie
* @package   Phergie_Plugin_Cache
* @author    Phergie Development Team <team@phergie.org>
* @copyright 2008-2010 Phergie Development Team (http://phergie.org)
* @license   http://phergie.org/license New BSD License
* @link      http://pear.phergie.org/package/Phergie_Plugin_Google
*/

/**
* A generic cache to be shared amongst other plugins.
*
* @category Phergie
* @package  Phergie_Plugin_Cache
* @author   Phergie Development Team <team@phergie.org>
* @license  http://phergie.org/license New BSD License
* @link     http://pear.phergie.org/package/Phergie_Plugin_Google
*/
class Phergie_Plugin_Cache extends Phergie_Plugin_Abstract
{
    /**
     * The cache inside the class.
     * 
     * @var array 
     */
    protected $cache = array();

    /**
     * Allows a value to be stored in the cache. Takes optional arguments of
     * Time To Live (TTL) and whether or not existing values can be overwritten.
     *
     * @param string  $key       The key to store data with.
     * @param mixed   $data      The data to be stored. Can be any valid data.
     * @param integer $ttl       The time to live. Can be null for forever.
     * @param boolean $overwrite Whether overwriting is permissible.
     *
     * @return boolean
     */
    public function store($key, $data, $ttl = 3600, $overwrite = true)
    {
        if (!$overwrite && isset($this->cache[$key])) {
            return false;
        }

        if ($ttl) {
            $expires = time()+$ttl;
        } else {
            $expires = null;
        }

        $this->cache[$key] = array('data' => $data, 'expires' => $expires);
        return true;

    }

    /**
     * Fetch a key that has been stored.
     *
     * @param string $key The key to be retreived.
     *
     * @return mixed
     */
    public function fetch($key)
    {
        if (!isset($this->cache[$key])) {
            return false;
        }

        $item = $this->cache[$key];
        if (!is_null($item['expires']) && $item['expires'] < time()) {
            $this->expire($key);
            return false;
        }

        return $item['data'];
    }

    /**
     * Expire a key who has outlived their time to live.
     *
     * @param string $key The key to be expired.
     *
     * @return boolean
     */
    protected function expire($key)
    {
        if (!isset($this->cache[$key])) {
            return false;
        }
        unset($this->cache[$key]);
        return true;
    }
}
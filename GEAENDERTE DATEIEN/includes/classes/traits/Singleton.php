<?php
/**
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Zcwilt 2020 May 23 New in v1.5.7 $
 */

namespace Zencart\Traits;

/**
 * @since ZC v1.5.7
 */
trait Singleton
{
    private static array $instances = [];

    protected function __construct() { }

    /**
     * @since ZC v1.5.7
     */
    protected function __clone() { }

    /**
     * @since ZC v2.2.0
     */
    public function __unserialize(array $data): void
    {
        throw new \BadMethodCallException("Cannot unserialize singleton");
    }

    /**
     * @since ZC v1.5.7
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * @since ZC v1.5.7
     */
    public static function getInstance()
    {
        $cls = get_called_class(); // late-static-bound class name
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];
    }
}

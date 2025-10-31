<?php
/**
 * auto-load and instantiate all /includes/classes/observers/auto.xxxxxxxxx.php classes
  *
 * This looks for any files in the DIR_WS_CLASSES/observers folder matching the naming convention of "auto.XXXXXX.php"
 * It then automatically "include"s those files.
 * And then it checks to see whether the XXXXXXXXX part of the filename matches a class name using "zcObserver" + the CamelCased XXXXXXXXX string.
 * ie: zcObserverTemplateFrameworkAbc would match auto.template_framework_abc.php
 * If the properly named class exists, then it instantiates that class using an object of the same name.  If the class inside the file is NOT properly named, it will NOT be instantiated, despite being loaded.
 *
 * The assumption is that the class is an observer class which properly extends the base class (or implements NotifierManager and ObserverManager)
 * All normal observer class behavior applies.
 *
 * This fires at AutoLoader point 175, so all previously-processed system dependencies are in place.
 * If you need an observer class to fire at a much earlier point so it fires before other system processes, you'll need to add your own auto_loaders/config.yyyyy.php file with relevant rules to load those observers.
 * Zen Cart German Specific (210 code in 157)
 * @copyright Copyright 2003-2025 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: init_observers.php 2025-10-30 08:21:16Z webchills $
 */

use Zencart\FileSystem\FileSystem;

if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

$observersMain = (new FileSystem)->listFilesFromDirectory(DIR_WS_CLASSES . 'observers/', '~(^(auto\.|Auto[A-Z]).*\.php$)~');
$observersMain = collect($observersMain)->map(fn($item, $key) => DIR_WS_CLASSES . 'observers/' . $item)->toArray();
$context = IS_ADMIN_FLAG ? 'admin' : 'catalog';
$observersPlugins = [];
foreach ($installedPlugins as $plugin) {
    $path = DIR_FS_CATALOG . 'zc_plugins/' . $plugin['unique_key'] . '/' . $plugin['version'] . '/' . $context . '/' . DIR_WS_CLASSES . 'observers/';
    $observersPlugin = (new FileSystem)->listFilesFromDirectory($path, '~(^(auto\.|Auto[A-Z]).*\.php$)~');
    $observersPlugin = collect($observersPlugin)->map(fn($item, $key) => $path . $item)->toArray();
    $observersPlugins = array_merge($observersPlugins, $observersPlugin);
}
$observers = array_merge($observersPlugins, $observersMain);

// sort by filename so that observers are loaded in a predictable order
$basenames = array_map('basename', $observers);
array_multisort(
    $basenames, SORT_ASC, SORT_NATURAL | SORT_FLAG_CASE,
    $observers, SORT_ASC, SORT_NATURAL | SORT_FLAG_CASE
);
unset($basenames);

// instantiate discovered observer classes
foreach ($observers as $observer) {
    if (!file_exists($observer)) {
        continue;
    }
    include $observer;
    $observerFilename = basename($observer);
    $className = preg_replace('~(^auto\.|\.php$)~', '', $observerFilename);
    $psr4ClassName = base::camelize($className, true);
    $objectName = 'zcObserver' . base::camelize($className, true);
    if (class_exists($objectName)) {
        // 'auto.' prefix in filename and 'zcObserver' prefix in class name
        $$objectName = new $objectName();
    } elseif (class_exists($psr4ClassName)) {
        // 'Auto' prefix in filename and class name matches filename
        $$objectName = new $psr4ClassName();
    } elseif (class_exists($alternateClassName = preg_replace('~^Auto~', '', $psr4ClassName))) {
        // 'Auto' prefix in filename but not in class name
        $$objectName = new $alternateClassName();
    } else {
        error_log(
            sprintf('ERROR: Observer class %s (or alternate class %s) could not be instantiated despite file %s being found. Please follow the correct naming convention for the class name inside the file.',
                $psr4ClassName, $objectName, $observer
            )
        );
    }
}

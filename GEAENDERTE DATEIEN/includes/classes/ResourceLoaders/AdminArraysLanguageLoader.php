<?php
/**
 * Zen Cart German Specific (210 code in 157)
 * @copyright Copyright 2003-2025 Zen Cart Development Team
 * Zen Cart German Version - www.zen-cart-pro.at
 * @copyright Portions Copyright 2003 osCommerce
 * @license https://www.zen-cart-pro.at/license/3_0.txt GNU General Public License V3.0
 * @version $Id: AdminArraysLanguageLoader.php 2025-10-27 17:27:24Z webchills $
 */

namespace Zencart\LanguageLoader;

/**
 * @since ZC v1.5.8
 */
class AdminArraysLanguageLoader extends ArraysLanguageLoader
{
    /**
     * @since ZC v1.5.8
     */
    public function loadInitialLanguageDefines($mainLoader): void
    {
        $this->mainLoader = $mainLoader;
        $this->loadBaseLanguageFile();
        $this->loadLanguageForView();
        $this->loadLanguageExtraDefinitions();
    }

    /**
     * @since ZC v1.5.8
     */
    protected function loadLanguageForView(): void
    {
        $this->loadDefinesFromDirFileWithFallback(DIR_WS_LANGUAGES, $this->currentPage);

        $defineList = $this->pluginLoadDefinesFromArrayFile($this->fallback, $this->currentPage, 'admin', '');
        $this->addLanguageDefines($defineList);

        if ($_SESSION['language'] !== $this->fallback) {
            $defineList = $this->pluginLoadDefinesFromArrayFile($_SESSION['language'], $this->currentPage, 'admin', '');
            $this->addLanguageDefines($defineList);
        }
    }

    /**
     * @since ZC v1.5.8
     */
    protected function loadLanguageExtraDefinitions(): void
    {
        $defineList = $this->loadArraysFromDirectory(DIR_WS_LANGUAGES, $this->fallback, '/extra_definitions');
        $this->addLanguageDefines($defineList);

        if ($_SESSION['language'] !== $this->fallback) {
            $defineList = $this->loadArraysFromDirectory(DIR_WS_LANGUAGES, $_SESSION['language'], '/extra_definitions');
            $this->addLanguageDefines($defineList);
        }

        $defineList = $this->pluginLoadArraysFromDirectory($this->fallback, '/extra_definitions');
        $this->addLanguageDefines($defineList);

        if ($_SESSION['language'] !== $this->fallback) {
            $defineList = $this->pluginLoadArraysFromDirectory($_SESSION['language'], '/extra_definitions');
            $this->addLanguageDefines($defineList);
        }
    }

    /**
     * @since ZC v2.1.0
     */
    protected function loadBaseLanguageFile()
    {
        // -----
        // First, load the main language file(). The 'lang.english.php' file is always
        // loaded, with its constant values possibly overwritten by a different main
        // language file (e.g. lang.spanish.php).
        //
        // These definitions are added to the to-be-generated constants' list.
        //
        $mainFile = DIR_WS_LANGUAGES . 'lang.' . $_SESSION['language'] . '.php';
        $fallbackFile = DIR_WS_LANGUAGES . 'lang.' . $this->fallback . '.php';
        $defineList = $this->loadDefinesWithFallback($mainFile, $fallbackFile);
        $this->addLanguageDefines($defineList);

        // -----
        // Next, load some other files with multi-page-use constants, adding their
        // definitions to the to-be-generated constants' list.
        //
        // Each file is first loaded from the 'english' sub-directory for the given
        // directory and then, if the session's language is non-english, overwritten
        // by any such file found in that language sub-directory (e.g. 'spanish').
        //
        $this->loadDefinesFromDirFileWithFallback(DIR_WS_LANGUAGES, 'gv_name.php');
        $this->loadDefinesFromDirFileWithFallback(DIR_WS_LANGUAGES, FILENAME_EMAIL_EXTRAS);
        $this->loadDefinesFromDirFileWithFallback(DIR_FS_CATALOG . DIR_WS_LANGUAGES, FILENAME_OTHER_IMAGES_NAMES);

        // -----
        // Finally, if the 'lang.other_images_names.php' has a template-override file **in the
        // current session's language**, load those definitions, adding to the
        // to-be-generated constants' list.
        //
        if ($this->fileSystem->hasTemplateLanguageOverride($this->templateDir, DIR_FS_CATALOG . DIR_WS_LANGUAGES, $_SESSION['language'], FILENAME_OTHER_IMAGES_NAMES)) {
            $defineList = $this->loadDefinesFromArrayFile(DIR_FS_CATALOG . DIR_WS_LANGUAGES, $_SESSION['language'], FILENAME_OTHER_IMAGES_NAMES, '/' . $this->templateDir);
            $this->addLanguageDefines($defineList);
        }
    }
}

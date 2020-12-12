<?php

namespace TgJQuery;

/**
 * Makes sure that js subdirectory is writeable
 * @author ralph
 *        
 */
class ComposerCommands {

    /** Executed after the package was installed */
    public static function postInstall() {
        self::ensureWriteability();
    }
    
    /** Executed after the package was updated */
    public static function postUpdate() {
        self::ensureWriteability();
    }
    
    protected static function ensureWriteability() {
        chmod(__DIR__.'/../../js', "777");
    }
}


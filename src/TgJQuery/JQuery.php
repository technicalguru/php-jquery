<?php

namespace TgJQuery;

/**
 * Provides jQuery library.
 */
class JQuery {


    public const UNCOMPRESSED = '';

    public const MINIFIED = 'min';

    public const SLIM = 'slim';

    public const SLIM_MINIFIED = 'slim.min';

    private static $BASE_URI = 'http://code.jquery.com/jquery/';
    private static $versions = NULL;
    
    /**
     * Returns all versions available.
     *
     * @return a list of available versions
     */
    public static function getVersions() {
        if (self::$versions == NULL) {
            $coreDownloadPage = file_get_contents(self::$BASE_URI);
            $matches = array();
            if (preg_match_all('/<a (class=\'open-sri-modal\' )?href=["\']\\/jquery-([^"\']*).js["\']/', $coreDownloadPage, $matches)) {
                $versions = array();
                foreach ($matches[2] as $version) {
                    $orig = $version;
                    $pos = strpos($version, '.min');
                    if ($pos !== FALSE) $version = substr($version, 0, $pos);
                    $pos = strpos($version, '.slim');
                    if ($pos !== FALSE) $version = substr($version, 0, $pos);
                    $pos = strpos($version, '.pack');
                    if ($pos !== FALSE) $version = substr($version, 0, $pos);
                    $versions[$version] = 1;
                }
                self::$versions = array_keys($versions);
            } else {
                throw new \Exception('Cannot find jQuery versions');
            }
        }
        return self::$versions;
    }

    /**
     * Returns the latest version.
     * @param string $majorVersion - the major version to look for
     * @return string the latest version or latest version of $majorVersion.
     */
    public static function getLatest($majorVersion = NULL) {
        $versions = self::getVersions();
        $ignore   = FALSE;
        
        // Only use numbered versions when no major was given
        if ($majorVersion == NULL) {
            if (preg_match('/^[^\\d]/', $version)) {
                $ignore = TRUE;
            }
        }
        
        // Always ignore -git versions (they are not latest/stable)
        if (strpos($version, '-git')) {
            $ignore = TRUE;
        }

        if (!$ignore) {
            if (($majorVersion == NULL) || (strpos($version, $majorVersion) === 0)) {
                if (($rc == NULL) || (version_compare($version, $rc) > 0)) {
                    $rc = $version;
                }
            }
        }

        if ($rc == NULL) throw new \Exception('Cannot find latest jQuery version');
        return $rc;
    }
    
    /**
     * Returns the URI from where the script library can be downloaded.
     * <p>For a specific application you shall stick with a specific version, e.g. 3.5.1</p>
     *
     * @param string $version
     *            - the version to deliver (optional: default is latest). Check the output of #getVersions().
     * @param string $type
     *            - type of link, can be UNCOMPRESSED, MINIFIED, SLIM, SLIM_MINIFIED (optional, default is MINIFIED)
     * @param boolean $fromRemote
     *            - whether a CDN link can be delivered (optional, default is FALSE)
     * @return string the URI to the jQuery library
     */
    public static function getUri($version = 'latest', $type = MINIFIED, $fromRemote = FALSE) {
        if ($version == 'latest') {
            $version = self::getLatest();
        }
        $rc = self::BASE_URI.'/jquery-'.$version;
        if ($type) {
            $rc .= '.'.$type;
        }
        return $rc.'.js';
    }

    /**
     * Returns the HTML script tag for a web page.
     *
     * @param string $version
     *            - the version to deliver (optional: default is latest). Check the output of #getVersions().
     * @param string $type
     *            - type of link, can be UNCOMPRESSED, MINIFIED, SLIM, SLIM_MINIFIED (optional, default is MINIFIED)
     * @param boolean $fromRemote
     *            - whether a CDN link can be delivered (optional, default is FALSE)
     * @return string the HTML script tag to the jQuery library
     */
    public static function getLink($version = 'latest', $type = MINIFIED, $fromRemote = FALSE) {
        return '<script type="text/javascript" src="'.self::getUri($version, $type, $fromRemote).'"></script>';
    }
}
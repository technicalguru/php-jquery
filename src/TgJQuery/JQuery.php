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

    private static $BASE_URI = 'http://code.jquery.com';
    private static $versions = NULL;
    
    /**
     * Returns all versions available.
     *
     * @return a list of available versions
     */
    public static function getVersions() {
        if (self::$versions == NULL) {
            $coreDownloadPage = file_get_contents(self::$BASE_URI.'/jquery/');
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
                throw new JQueryException('Cannot find jQuery versions');
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

		foreach ($versions AS $version) {
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
		}

        if ($rc == NULL) throw new JQueryException('Cannot find latest jQuery version', $majorVersion);
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
    public static function getUri($version = 'latest', $type = JQuery::MINIFIED, $fromRemote = FALSE) {
        if ($version == 'latest') {
            $version = self::getLatest();
		}

		// Construct filename
		$filename = 'jquery-'.$version;
        if ($type) {
            $$filename .= '.'.$type;
        }
		$filename .= '.js';

		// Remote URL
		$remote    = self::$BASE_URI.'/'.$filename;
		$rc        = $remote;

		// Local caching
		if (!$fromRemote) {
			$localPath = realpath(__DIR__.'/../../js').'/'.$filename;
			if (!file_exists($localPath)) {
				$js = file_get_contents($remote);
				if ($js === FALSE) throw new JQueryException('Cannot load from remote jQuery URI', $remote);
				$ok = file_put_contents($localPath, $js);
				echo "localPath = $localPath<br>";
				if ($ok === FALSE) throw new JQueryException('Cannot write jQuery to local disk', $localPath);
			}
			$docRoot = $_SERVER['CONTEXT_DOCUMENT_ROOT'] ? $_SERVER['CONTEXT_DOCUMENT_ROOT'] : $_SERVER['_DOCUMENT_ROOT'];
			$local   = '/';
			if (strpos($localPath, $docRoot) === 0) $local = substr($localPath, strlen($docRoot));
			else throw new JQueryException('Cannot compute local path', $localPath);
			$rc = $local;
		}

        return $rc;
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

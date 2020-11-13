# php-jquery
This library provides an easy way to get any jQuery libary included in your PHP project. This includes the usage of the remote, original jQuery libarary, or caching it locally on your server. Features include:

* Checking for the latest jQuery version
* Automatically use fix versions for any major version
* Using any flavour jQuery library: minified, slim, uncompressed or slim-minified.
* Listing all available versions
* Using git versions

# License
This project is licensed under [GNU LGPL 3.0](LICENSE.md). 

# Installation

## By Composer

```
composer install technicalguru/jquery
```

## By Package Download
You can download the source code packages from [GitHub Release Page](https://github.com/technicalguru/php-jquery/releases)

# How to use

## Listing available versions

```
$versionList = \TgJQuery\JQuery::getVersions();
```

This method lists all available versions.

## Get the latest version number

The most recent stable version can be fetched by:

```
$lastestVersionNumber = \TgJQuery\JQuery::getLatest();
```

In case you are interested in the latest 3.3 fix:

```
$lastestVersionNumber = \TgJQuery\JQuery::getLatest('3.3');
```

## Get the URI of a remote library

The following method will give you URIs for your further inspection:

```
use \TgJQuery\JQuery;

// Get URI to latest version, as uncompressed JS
$uri = JQuery::getUri('latest', JQuery::UNCOMPRESSED, TRUE);

// Get URI to 3.5.1 version, as slim JS
$uri = JQuery::getUri('3.5.1', JQuery::SLIM, TRUE);

// Get URI to latest 3.x git build, as minified JS
$uri = JQuery::getUri('3.x-git', JQuery::MINIFIED, TRUE);
```

The boolean parameter `TRUE` tells JQuery to use remote versions only and not to cache it.

You can get the correct HTML script tag to be included in your HTML output in the same way:

```
use \TgJQuery\JQuery;

// Get HTML link to latest version, as uncompressed JS
$link = JQuery::getLink('latest', JQuery::UNCOMPRESSED, TRUE);

// Get HTML link to 3.5.1 version, as slim JS
$link = JQuery::getLink('3.5.1', JQuery::SLIM, TRUE);

// Get HTML link to latest 3.x git build, as minified JS
$link = JQuery::getLink('3.x-git', JQuery::MINIFIED, TRUE);
```

## Caching the jQuery library
Most projects prefer not to serve the jQuery library from remote CDNs but rather locally. This
is also the default. That's why the `getUri()` and `getLink()` calls can be written much shorter.

```
use \TgJQuery\JQuery;

// Get a local cache URI to latest version, as minified JS
$uri = JQuery::getUri('latest');

// Get local cache HTML link to 3.5.1 version, as slim minified JS
$link = JQuery::getLink('3.5.1', JQuery::SLIM_MINIFIED);
```

## Performance Considerations
The current version triggers remote calls to [http://code.jquery.com](http://code.jquery.com) whenever
you call `getLatest()` and `getVersions()` the first time in your script. The version list is
not being cached across multiple HTTP requests. This will be added in a later version.

However, it is recommended to stick with a defined version in your application to avoid side effects
when jQuery dynamically upgrades in the background.

# Contribution
Report a bug, request an enhancement or pull request at the [GitHub Issue Tracker](https://github.com/technicalguru/php-jquery/issues).


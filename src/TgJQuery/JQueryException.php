<?php

namespace TgJQuery;

/**
 * Exception for any jQuery related errors.
 * <p>The $info field can contain additional information about the error.</p>
 */
class JQueryException extends \Exception {

	public $info;

	/**
	 * Constructor.
	 * @param string $message - the error message
	 * @param mixed  $info    - addition information
	 */
    public function __construct($message, $info = NULL) {
		parent::__construct($message);
		$this->info = $info;
    }

	/**
	 * Returns the additional information.
	 * @return mixed additional information.
	 */
    public function getInfo() {
        return $this->info;
	}

    public function __toString() {
        return __CLASS__ . ": {$this->message}\n";
    }

}

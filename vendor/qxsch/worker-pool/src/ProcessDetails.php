<?php
/**
 * The Process Details
 */

namespace QXS\WorkerPool;

/**
 * The Process Details Class
 */
class ProcessDetails {

	/** @var int process id pid */
	protected $pid;

	/** @var SimpleSocket the socket */
	protected $socket;

	/**
	 * The constructor
	 * @param int $pid
	 * @param SimpleSocket $socket
	 */
	public function __construct($pid, SimpleSocket $socket) {
		$this->pid = $pid;
		$this->socket = $socket;
		$this->socket->annotation['pid'] = $pid;
	}

	/**
	 * Sanitizes the process title format string
	 * @param string $string the process title
	 * @return string the process sanitized title
	 * @throws \DomainException in case the $string value is not within the permitted range
	 */
	public static function sanitizeProcessTitleFormat($string) {
		$string = preg_replace(
			'/[^a-z0-9-_.:% \\\\\\]\\[]/i',
			'',
			$string
		);
		$string = trim($string);
		return $string;
	}

	/**
	 * Sets the proccess title
	 *
	 * This function call requires php5.5+ or the proctitle extension!
	 * Empty title strings won't be set.
	 * @param string $title the new process title
	 * @param array $replacements an associative array of replacment values
	 * @return void
	 */
	public static function setProcessTitle($title, array $replacements = array()) {
		// skip when empty title names or running on MacOS
		if (trim($title) == '' || PHP_OS == 'Darwin') {
			return;
		}
		// 1. replace the values
		$title = preg_replace_callback(
			'/\%([a-z0-9]+)\%/i',
			function ($match) use ($replacements) {
				if (isset($replacements[$match[1]])) {
					return $replacements[$match[1]];
				}
				return $match[0];
			},
			$title
		);
		// 2. remove forbidden chars
		$title = preg_replace(
			'/[^a-z0-9-_.: \\\\\\]\\[]/i',
			'',
			$title
		);
		// 3. set the title
		if (function_exists('cli_set_process_title')) {
			cli_set_process_title($title); // PHP 5.5+ has a builtin function
		} elseif (function_exists('setproctitle')) {
			setproctitle($title); // pecl proctitle extension
		}
	}

	/**
	 * Get the pid
	 * @return int
	 */
	public function getPid() {
		return $this->pid;
	}

	/**
	 * Get the socket
	 * @return \QXS\WorkerPool\SimpleSocket
	 */
	public function getSocket() {
		return $this->socket;
	}
}

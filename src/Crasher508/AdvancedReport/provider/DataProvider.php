<?php
declare(strict_types=1);
namespace Crasher508\AdvancedReport\provider;

use Crasher508\AdvancedReport\Report;
use Crasher508\AdvancedReport\Main;

abstract class DataProvider
{
	/** @var Main $plugin */
	protected $plugin;

	/**
	 * DataProvider constructor.
	 *
	 * @param Main $plugin
	 */
	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
	}

	/**
	 * @param Report $report
	 *
	 * @return bool
	 */
	public abstract function addReport(Report $report) : bool;

	/**
	 * @param Report $report
	 *
	 * @return bool
	 */
	public abstract function removeReport(Report $report) : bool;

	/**
	 * @param string $reporter
	 * @param string $player
	 *
	 * @return Report|null
	 */
	public abstract function getReport(string $reporter, string $player) : ?Report;

	/**
	 *
	 * @return Report[]
	 */
	public abstract function getAllReports() : array;

	public abstract function close() : void;
}
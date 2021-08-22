<?php
declare(strict_types=1);
namespace Crasher508\AdvancedReport;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use Crasher508\AdvancedReport\provider\DataProvider;
use Crasher508\AdvancedReport\provider\SQLiteDataProvider;
use Crasher508\AdvancedReport\commands\ReportCommand;
use pocketmine\lang\BaseLang;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
	private static Main $instance;
	private ?DataProvider $dataProvider = null;
	private ?BaseLang $baseLang = null;

	public string $prefix = "§l§aAdvancedReport §d> §r";

	/**
	 * @return Main
	 */
	public static function getInstance() : self {
		return self::$instance;
	}

	/**
	 * Returns the Multi-lang management class
	 *
	 * @api
	 *
	 * @return BaseLang
	 */
	public function getLanguage() : BaseLang {
		return $this->baseLang;
	}

	/**
	 * Returns the DataProvider that is being used
	 *
	 * @api
	 *
	 * @return DataProvider
	 */
	public function getProvider() : DataProvider {
		return $this->dataProvider;
	}

	public function onLoad() : void {
		self::$instance = $this;
		$this->reloadConfig();
		$lang = $this->getConfig()->get("Language", BaseLang::FALLBACK_LANGUAGE);
		$this->baseLang = new BaseLang($lang, $this->getFile() . "resources/");
		$this->dataProvider = new SQLiteDataProvider($this);
	}

	public function onEnable() : void {
		$this->getServer()->getCommandMap()->register("report", new ReportCommand($this));
	}

     /**
     * @param string $str
     * @param string[] $params
     *
     * @param string $onlyPrefix
     * @return string
     */
    public function translateString(string $str, array $params = [], string $onlyPrefix = null) : string {
        return $this->getLanguage()->translateString($str, $params, $onlyPrefix);
    }

	public function sendMessage(string $msg){
        $webhook = new Webhook($this->getConfig()->get("Webhook"));
        $message = new Message();
        $message->setUsername($this->getConfig()->get("WebhookName", "AdvancedReport"));
        $message->setContent($msg);
        $webhook->send($message);
    }

	public function onDisable() : void {
		if($this->dataProvider !== null)
			$this->dataProvider->close();
	}
}

<?php
declare(strict_types=1);
namespace Crasher508\AdvancedReport;

use MyPlot\MyPlot;
use Crasher508\AdvancedReport\provider\DataProvider;
use Crasher508\AdvancedReport\provider\MySQLProvider;
use Crasher508\AdvancedReport\provider\SQLiteDataProvider;
use Crasher508\AdvancedReport\commands\ReportCommand;
use pocketmine\lang\BaseLang;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase
{
	/** @var Main $instance */
	private static $instance;
	/** @var DataProvider $dataProvider */
	private $dataProvider = null;
	/** @var BaseLang $baseLang */
	private $baseLang = null;

	public $prefix = "§l§aAdvancedReport §d> §r";

	public $players = [];

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
		$this->Banner();
		$this->getLogger()->info("§4AdvancedReport from Crasher508 was actived - Copyright by Crasher");
	}

	private function Banner()
    {
        $banner = strval(
            "\n" .
                "╔═══╗╔═══╗╔═══╗╔═══╗╔╗═╔╗╔═══╗╔═══╗\n" .
                "║╔══╝║╔═╗║║╔═╗║║╔══╝║║═║║║╔══╝║╔═╗║\n" .
                "║║═══║╚═╝║║╚═╝║║╚══╗║╚═╝║║╚══╗║╚═╝║\n" .
                "║║═══║╔╗╔╝║╔═╗║╚══╗║║╔═╗║║╔══╝║╔╗╔╝\n" .
                "║╚══╗║║║╚╗║║═║║╔══╝║║║═║║║╚══╗║║║╚╗\n" .
                "╚═══╝╚╝╚═╝╚╝═╚╝╚═══╝╚╝═╚╝╚═══╝╚╝╚═╝"
        );
        $this->getLogger()->info($banner);
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

	/**
     * @param $message
     */
    // Heavy thanks to NiekertDev !

    public function sendMessage(string $player = "nolog", string $msg){
        $name = $this->getConfig()->get("WebhookName");
        $webhook = $this->getConfig()->get("Webhook");
        $curlopts = [
	    	"content" => $msg,
            "username" => $name
        ];
		
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $webhook);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curlopts));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($curl);
		return;
    }

	public function onDisable() : void {
		if($this->dataProvider !== null)
			$this->dataProvider->close();
	}
}

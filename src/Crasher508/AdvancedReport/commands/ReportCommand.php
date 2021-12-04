<?php

namespace Crasher508\AdvancedReport\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Crasher508\AdvancedReport\Main;
use Crasher508\AdvancedReport\forms\SimpleReportForm;
use Crasher508\AdvancedReport\forms\AdminReportForm;
use pocketmine\player\Player;

class ReportCommand extends Command
{

    private Main $plugin;

    public function __construct(Main $plugin)
    {
        parent::__construct("report");
        $this->setPermission("report.command.add");
		$this->setAliases([]);
		$this->setDescription("Melde einen Spieler");
		$this->setUsage("/report");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool
    {
    	if(!($sender instanceof Player)) {
			$sender->sendMessage($this->plugin->prefix . $this->plugin->translateString("noconsole"));
			return false;
		}
        if(!$sender->hasPermission("report.command.add")){
            $sender->sendMessage($this->plugin->prefix . $this->plugin->translateString("noperm"));
            return false;
        }
        if(empty($args[0])){
            if($sender->hasPermission("report.command.read")){
                $form = new AdminReportForm();
                $sender->sendForm($form);
            }else{
				$players = [];
				foreach(Main::getInstance()->getServer()->getOnlinePlayers() as $online){
					if((($add = $online->getName()) !== $sender->getName()) and !$online->hasPermission("report.bypass")) {
						$players[] = $add;
					}
				}
				if(count($players) < 1) {
					$sender->sendMessage($this->plugin->prefix . $this->plugin->translateString("addreport.noplayers"));
					return false;
				}
                $form = new SimpleReportForm($players);
                $sender->sendForm($form);
            }
            return false;
        }
        return true;
    }
}
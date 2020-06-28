<?php

namespace Crasher508\AdvancedReport\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;
use pocketmine\utils\Config;
use Crasher508\AdvancedReport\Main;
use pocketmine\utils\TextFormat;
use Crasher508\AdvancedReport\forms\SimpleReportForm;
use Crasher508\AdvancedReport\forms\AdminReportForm;

class ReportCommand extends PluginCommand
{

    public function __construct(Main $plugin)
    {
        parent::__construct("report", $plugin);
        $this->setPermission("report.command.add");
		$this->setAliases([]);
		$this->setDescription("Melde einen Spieler");
		$this->setUsage("/report");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender->hasPermission("report.command.add")){
            $sender->sendMessage($this->plugin->prefix . $this->plugin->translateString("noperm"));
            return false;
        }
        if(empty($args[0])){
            if($sender->hasPermission("report.command.read")){
                $form = new AdminReportForm();
                $sender->sendForm($form);
            }else{
                $form = new SimpleReportForm();
                $sender->sendForm($form);
            }
            return false;
        }
        return true;
    }
}
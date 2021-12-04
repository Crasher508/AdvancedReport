<?php

namespace Crasher508\AdvancedReport\forms;

use Crasher508\AdvancedReport\Main;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;

class AdminReportForm extends SimpleForm {

    public function __construct() {

        $callable = function (Player $player, $data) {

            if($data === null)
                return;

            switch ($data) {
                case 0:
                    break;
                case 1:
					$players = [];
					foreach(Main::getInstance()->getServer()->getOnlinePlayers() as $online){
						if((($add = $online->getName()) !== $player->getName()) and !$online->hasPermission("report.bypass")) {
							$players[] = $add;
						}
					}
					if(count($players) < 1) {
						$player->sendMessage(Main::getInstance()->prefix . Main::getInstance()->translateString("addreport.noplayers"));
						return;
					}
                    $form = new SimpleReportForm($players);
                    $player->sendForm($form);
                    break;
                case 2:
                    if($player->hasPermission("report.command.read")){
                        $reports = Main::getInstance()->getProvider()->getAllReports();
                        if(count($reports) > 0){
                            $form = new ReadListReportForm();
                            $player->sendForm($form);
                        }else{
                            $player->sendMessage(Main::getInstance()->prefix . Main::getInstance()->translateString("noreports"));
                        }
                    }else{
                        $player->sendMessage(Main::getInstance()->prefix . Main::getInstance()->translateString("noperm"));
                    }
                    break;
            }
        };

        parent::__construct($callable);

        $this->setTitle("§l§aAdvancedReport Dashboard");

        $this->setContent(Main::getInstance()->translateString("adminreport.content"));

        $this->addButton(Main::getInstance()->translateString("adminreport.close"));

        $this->addButton(Main::getInstance()->translateString("adminreport.create"));

        $this->addButton(Main::getInstance()->translateString("adminreport.manage"));

    }

}
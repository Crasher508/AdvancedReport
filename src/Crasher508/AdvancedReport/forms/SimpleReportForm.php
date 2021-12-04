<?php

namespace Crasher508\AdvancedReport\forms;

use Crasher508\AdvancedReport\Main;
use Crasher508\AdvancedReport\Report;
use jojoe77777\FormAPI\CustomForm;
use DateTime;
use pocketmine\player\Player;

class SimpleReportForm extends CustomForm {

    public function __construct(array $players) {
    	$callable = function (Player $player, $data) use ($players) {

            if ($data === null)
                return;

            $reportet = $players[$data[0]];
            $reason = Main::getInstance()->getConfig()->get("Reasons", ["Spam"])[$data[1]];
            $info = $data[2];
            if($info !== null and $info !== ""){
                if(Main::getInstance()->getProvider()->getReport($player->getName(), $reportet) === null){
                    $time = new DateTime("now", new \DateTimeZone("Europe/Berlin"));
                    $time = $time->format("d.m.Y H:i");
                    $report = new Report($reason, $player->getName(), $reportet, $info, $time);
                    Main::getInstance()->getProvider()->addReport($report);
                    $player->sendMessage(Main::getInstance()->prefix . Main::getInstance()->translateString("addreport.success", [$reportet]));

                    if(Main::getInstance()->getConfig()->get("SendToDiscord")){
                        $msg = Main::getInstance()->translateString("addreport.discord", [$player->getName(), $reportet, $reason, $info]);
                        Main::getInstance()->sendMessage($msg);
                    }
                }else{
                    $player->sendMessage(Main::getInstance()->prefix . Main::getInstance()->translateString("addreport.alwaysreport"));
                }
            }else{
                $player->sendMessage(Main::getInstance()->prefix . Main::getInstance()->translateString("addreport.emptyinput"));
            }
        };

        parent::__construct($callable);

        $this->setTitle("§l§aAdvancedReport");

        $this->addDropdown(Main::getInstance()->translateString("addreport.formchoose"), $players);

        $this->addDropdown(Main::getInstance()->translateString("addreport.formreason"), Main::getInstance()->getConfig()->get("Reasons", ["Spam"]));

        $this->addInput(Main::getInstance()->translateString("addreport.forminfo"));

    }

}
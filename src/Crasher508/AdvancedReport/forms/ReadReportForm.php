<?php

namespace Crasher508\AdvancedReport\forms;

use Crasher508\AdvancedReport\Main;
use Crasher508\AdvancedReport\Report;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;

class ReadReportForm extends SimpleForm {

    public function __construct(Report $report) {

        $callable = function (Player $player, $data) use ($report){

            if ($data === null) {



            } else {

                if($player->hasPermission("report.command.delete")){
                    Main::getInstance()->getProvider()->removeReport($report);
                    $player->sendMessage(Main::getInstance()->prefix . Main::getInstance()->translateString("readreport.successdelete", [$report->player, $report->reason]));
                }else{
                    $player->sendMessage(Main::getInstance()->prefix . Main::getInstance()->translateString("noperm"));
                }

            }

        };

        parent::__construct($callable);

        $this->setTitle("§l§aAdvancedReport Dashboard");

        $this->setContent(Main::getInstance()->translateString("readreport.content", [$report->player, $report->reason, $report->reporter, $report->date, $report->info]));
        $this->addButton(Main::getInstance()->translateString("readreport.delte"));

    }

}
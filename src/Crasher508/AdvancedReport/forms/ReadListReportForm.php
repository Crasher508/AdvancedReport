<?php

namespace Crasher508\AdvancedReport\forms;

use Crasher508\AdvancedReport\Main;
use Crasher508\AdvancedReport\forms\ReadReportForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;

class ReadListReportForm extends SimpleForm {

    public function __construct() {

        $callable = function (Player $player, $data) {

            if ($data === null) {



            } else {

                $report = Main::getInstance()->getProvider()->getAllReports()[$data];

                $form = new ReadReportForm($report);
                $player->sendForm($form);

            }

        };

        $reports = Main::getInstance()->getProvider()->getAllReports();

        parent::__construct($callable);

        $this->setTitle("§l§aAdvancedReport Dashboard");
            
        foreach($reports as $report){
            $this->addButton("§c" . $report->player . " - " . $report->reason);
        }

    }

}
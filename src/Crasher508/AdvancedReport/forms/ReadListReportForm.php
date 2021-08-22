<?php

namespace Crasher508\AdvancedReport\forms;

use Crasher508\AdvancedReport\Main;
use Crasher508\AdvancedReport\forms\ReadReportForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;

class ReadListReportForm extends SimpleForm {

    public function __construct() {

        $reports = Main::getInstance()->getProvider()->getAllReports();

        $callable = function (Player $player, $data) use ($reports) {

            if ($data === null)
                return;

            $report = $reports[$data];

            $form = new ReadReportForm($report);
            $player->sendForm($form);
        };

        parent::__construct($callable);

        $this->setTitle("§l§aAdvancedReport Dashboard");
            
        foreach($reports as $report){
            $this->addButton("§c" . $report->player . " - " . $report->reason);
        }

    }

}
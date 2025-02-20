<?php

declare(strict_types=1);

/**
 * Configuration GUI for CustomURLDisplay Plugin
 * 
 */
class ilCustomURLDisplayConfigGUI extends ilPluginConfigGUI {

    private $tpl;
    private $ctrl;

    public function __construct() {
        global $tpl, $ilCtrl;
        $this->tpl = $tpl;
        $this->ctrl = $ilCtrl;
    }

    // Handles commands 
    // @param string $cmd
    public function performCommand($cmd): void {
        switch ($cmd) {
            case "configure":
                $this->showConfig();
                break;
            case "save":
                $this->saveConfig();
                break;
            default:
                break;
        }
    }

    // TODO: Display the configuration Form
    private function showConfig(): void {
        $form = $this->buildForm();
        $this->tpl->setContent($form->getHTML());
    }


    private function buildForm(): ilPropertyFormGUI {

        $form = new ilPropertyFormGUI();
        $form->setTitle("URL Display Configuration");
        $form->setFormAction($this->ctrl->getFormAction($this));

        // protocol
        $protocol = new ilSelectInputGUI("URL PROTOCOL", "protocol");
        $protocol->setOptions(["http" => "http", "https" => "https"]);
        $protocol->setRequired(true);
        $protocol->setValue("https");
        $form->addItem($protocol);

        // Domain
        $domain = new ilTextInputGUI("DOMAIN", "domain");
        $domain->setRequired(true);
        $domain->setValue("");
        $form->addItem($domain);
        
        // port
        $port = new ilNumberInputGUI("PORT", "port");
        $port->setMinValue(1);
        $port->setMaxValue(65535);
        $port->setValue("");
        $form->addItem($port);

        // path
        $path = new ilTextInputGUI("PATH", "path");
        $path->setValue("");
        $form->addItem($path);

        // color
        $color = new ilSelectInputGUI("Background Color", "color");
        $color->setOptions(["red" => "Red", "blue" => "Blue", "green" => "Green"]);
        $color->setValue("red");
        $form->addItem($color);

        // Submit button
        $form->addCommandButton("save", "SAVE");

        return $form;

    }


    // TODO: Saves the configuration to the database
    private function saveConfig(): void {

    }
}

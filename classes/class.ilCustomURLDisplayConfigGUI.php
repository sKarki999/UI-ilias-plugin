<?php

declare(strict_types=1);

/**
 * Configuration GUI for CustomURLDisplay Plugin
 * 
 * @ilCtrl_IsCalledBy ilCustomURLDisplayConfigGUI: ilObjComponentSettingsGUI
 * 
 */
class ilCustomURLDisplayConfigGUI extends ilPluginConfigGUI {

    /** @var ilGlobalTemplateInterface Main template instance */
    private ilGlobalTemplateInterface $tpl;

    /** @var ilCtrl Controller instance for navigation */
    private ilCtrl $ctrl;

    /** @var ilDBInterface Database connection */
    private ilDBInterface $db;

    /** @var object Dependency Injection Container */
    private $dic;

    public function __construct() {
        global $DIC;
        $this->dic = $DIC;
        $this->db = $this->dic->database();
        $this->ctrl = $this->dic->ctrl();
        $this->tpl = $this->dic->ui()->mainTemplate();
    }

    /**
     * Method for handling commands
     *
     * @param string $cmd
     * @return void
     */
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

    // Display the configuration Form
    private function showConfig(): void {
        $form = $this->buildForm();
        $this->tpl->setContent($form->getHTML());
    }


    /**
     * Builds the configuration form for the plugin
     * @return ilPropertyFormGUI
     */
    private function buildForm(): ilPropertyFormGUI {

        // Fetch existing values(last inserted id) or set defaults if no data exists
        $result = $this->db->query("SELECT * FROM uihk_url_display ORDER BY id DESC LIMIT 1");
        $values = ($this->db->numRows($result) > 0) ? $this->db->fetchAssoc($result) : [];

        $form = new ilPropertyFormGUI();
        $form->setTitle($this->getPluginObject()->txt("url_display_title"));
        $form->setFormAction($this->ctrl->getFormAction($this));

        // protocol
        $protocol = new ilSelectInputGUI($this->getPluginObject()->txt("url_display_protocol"), "protocol");
        $protocol->setOptions(["http" => "http", "https" => "https"]);
        $protocol->setRequired(true);
        $protocol->setValue($values["protocol"] ?? "https");
        $form->addItem($protocol);

        // Domain
        $domain = new ilTextInputGUI($this->getPluginObject()->txt("url_display_domain"), "domain");
        $domain->setRequired(true);
        $domain->setValue($values["domain"] ?? "");
        $form->addItem($domain);

        // port
        $port = new ilNumberInputGUI($this->getPluginObject()->txt("url_display_port"), "port");
        $port->setMinValue(1);
        $port->setMaxValue(65535);
        $port->setValue($values["port"] ?? "");
        $form->addItem($port);

        // path
        $path = new ilTextInputGUI($this->getPluginObject()->txt("url_display_path"), "path");
        $path->setValue($values["path"] ?? "");
        $form->addItem($path);

        // color
        $color = new ilSelectInputGUI($this->getPluginObject()->txt("url_display_color"), "color");
        $color->setOptions([
            "mistyrose" => "Misty Rose",
            "palegreen" => "Pale Green",
            "khaki"     => "Khaki",
            "lightblue" => "Light Blue",
        ]);
        $color->setValue($values["color"] ?? "red");
        $form->addItem($color);

        // Submit button
        $form->addCommandButton("save", "SAVE");

        return $form;
    }


    // Saves the configuration to the database
    private function saveConfig(): void {

        $form = $this->buildForm();

        if ($form->checkInput()) {
            // get the values from the form input
            $protocol = $form->getInput("protocol");
            $domain = $form->getInput("domain");
            $port = $form->getInput("port");
            $path = $form->getInput("path");
            $color = $form->getInput("color");

            // Update values in database
            $this->db->replace('uihk_url_display', [], [
                "protocol" => ["text", $protocol],
                "domain" => ["text", $domain],
                "port" => ["integer", $port],
                "path" => ["text", $path],
                "color" => ["text", $color]

            ]);

            // Show success message
            $this->dic->ui()->mainTemplate()->setOnScreenMessage(
                "success",
                $this->getPluginObject()->txt("url_display_success"),
                true
            );

            // Redirect back to Configuration page
            $this->ctrl->redirectByClass("ilCustomURLDisplayConfigGUI", "configure");
        }
    }
}

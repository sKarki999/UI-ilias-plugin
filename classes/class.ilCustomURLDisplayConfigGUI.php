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
    private ilGlobalTemplateInterface $template;

    /** @var ilCtrl Controller instance for navigation */
    private ilCtrl $controller;

    /** @var ilDBInterface Database connection */
    private ilDBInterface $database;

    private $container;

    public function __construct() {
        global $DIC;
        $this->container = $DIC;
        $this->database = $this->container->database();
        $this->controller = $this->container->ctrl();
        $this->template = $this->container->ui()->mainTemplate();
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
        $this->template->setContent($form->getHTML());
    }


    /**
     * Builds the configuration form for the plugin
     * @return ilPropertyFormGUI
     */
    private function buildForm(): ilPropertyFormGUI {

        // Fetch existing values(last inserted id) or set defaults if no data exists
        $result = $this->database->query("SELECT * FROM uihk_url_display ORDER BY id DESC LIMIT 1");
        $values = ($this->database->numRows($result) > 0) ? $this->database->fetchAssoc($result) : [];

        $form = new ilPropertyFormGUI();
        $form->setTitle($this->getPluginObject()->txt("url_display_title"));
        $form->setFormAction($this->controller->getFormAction($this));

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

            try {

                // save values in database
                $this->database->replace('uihk_url_display', [], [
                    "protocol" => ["text", $protocol],
                    "domain" => ["text", $domain],
                    "port" => ["integer", $port],
                    "path" => ["text", $path],
                    "color" => ["text", $color]

                ]);

                $this->container->ui()->mainTemplate()->setOnScreenMessage("success", $this->getPluginObject()->txt("url_display_success"), true);
            } catch (Exception $e) {
                error_log("ERROR " . $e->getMessage());
            }

            // Show success message

            // Redirect back to Configuration page
            $this->controller->redirectByClass("ilCustomURLDisplayConfigGUI", "configure");
        }
    }
}

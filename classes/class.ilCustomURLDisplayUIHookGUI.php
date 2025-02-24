<?php

declare(strict_types=1);

/**
 * Class ilCustomURLDisplayUIHookGUI
 * 
 */
class ilCustomURLDisplayUIHookGUI extends ilUIHookPluginGUI {

    /** @var ilDBInterface Database connection */
    private ilDBInterface $database;

    private $container;

    public function __construct() {

        global $DIC;
        $this->container = $DIC;
        $this->database = $this->container->database();
    }

    /**
     * Modifies the HTML output of GUI elements.
     * Checks if the current component is "Services/Dashboard" and the part is "right_column".
     * If so, it appends a custom HTML block to display a URL.
     *
     * @param string $a_comp The component being rendered (e.g., "Services/Dashboard")
     * @param string $a_part The part of the UI being modified (e.g., "right_column")
     * @param array $a_par Additional parameters
     * @return array Mode and HTML content to be appended
     */
    public function getHTML($a_comp, $a_part, $a_par = []): array {

        // Targeting the right column of the ILIAS dashboard
        if ($a_comp === "Services/Dashboard") {

            // Fetch latest URL configuration from database
            $result = $this->database->query("SELECT * FROM uihk_url_display ORDER BY id DESC LIMIT 1");

            if ($this->database->numRows($result) > 0) {
                $config = $this->database->fetchAssoc($result);
            } else {
                // Default values if no configuration exists
                $config = [
                    "protocol" => "http",
                    "domain" => "www.example.com",
                    "port" => "80",
                    "path" => "/",
                    "color" => "red"
                ];
            }

            // Construct the full URL from the retrieved configuration
            $url = $config["protocol"] . "://" . $config["domain"];

            // Add port only if it exists
            if (!empty($config["port"])) {
                $url .= ":" . $config["port"];
            }

            // Add path only if it exists and ensure it starts with "/"
            if (!empty($config["path"])) {
                $url .= "/" . ltrim($config["path"], "/");
            }

            // template path
            $template_path = __DIR__ . "/../templates/tpl.custom_url_display.html";
            $template = new ilTemplate($template_path, true, true);
            $template->setVariable("URL", $url);
            $template->setVariable("COLOR", $config['color']);
            
            // Get the ILIAS main template instance
            $main_tpl = $this->container->ui()->mainTemplate();

            // Set the title of the ILIAS page
            $main_tpl->setTitle("ILIAS TEST");

            // Inject the content
            $main_tpl->setContent($template->get());
        }

        // Return an array with mode "KEEP" to ensure the default UI behavior is preserved
        return ["mode" => self::KEEP, "html" => ""];
    }

   
}

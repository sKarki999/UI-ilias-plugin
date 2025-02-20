<?php

declare(strict_types=1);

/**
 * Class ilCustomURLDisplayUIHookGUI
 * 
 */
class ilCustomURLDisplayUIHookGUI extends ilUIHookPluginGUI {


    private $db; // database connection

    public function __construct() {

        global $ilDB;
        $this->db = $ilDB;

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
        if($a_comp === "Services/Dashboard" && $a_part === "right_column") {

            // error_log("Injecting element into Dashboard Right Column");

            // Fetch latest URL configuration from database
            $result = $this->db->query("SELECT * FROM uihk_url_display ORDER BY id DESC LIMIT 1");

            if ($this->db->numRows($result) > 0) {
                $config = $this->db->fetchAssoc($result);
            } else {
                // Default values if no configuration exists
                $config = [
                    "protocol" => "http",
                    "domain" => "example.com",
                    "port" => "80",
                    "path" => "/",
                    "color" => "red"
                ];
            }

            $template_path = "./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/CustomURLDisplay/templates/tpl.custom_url_display.html";

            $html = file_get_contents($template_path);
            $html = str_replace(
                ["{PROTOCOL}", "{DOMAIN}", "{PORT}", "{PATH}", "{COLOR}"],
                [$config["protocol"], $config["domain"], $config["port"], $config["path"], $config["color"]],
                $html
            );

            return ["mode" => self::APPEND, "html" => $html];

        }

        return ["mode" => self::KEEP, "html" => ""];

    }
}

<?php

declare(strict_types=1);

/**
 * Configuration GUI for CustomURLDisplay Plugin
 * 
 */
class ilCustomURLDisplayConfigGUI extends ilPluginConfigGUI {


    public function __construct() {

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
        
    }

    // TODO: Saves the configuration to the database
    private function saveConfig(): void {

    }
}

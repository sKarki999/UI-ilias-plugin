<?php
declare(strict_types=1);

/**
 * URL Display User Interface Hook Plugin
 *  
 */
class ilCustomURLDisplayPlugin extends ilUserInterfaceHookPlugin {
    
    /**
     * @return string The name of the plugin
     */
    public function getPluginName(): string {
        return "CustomURLDisplay";
    }
    

}

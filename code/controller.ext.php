<?php

/**
 * Powerplug is a module that enables you to shutdown or restart your server from the ZPanel web interface.
 * Developed by Bobby Allen (bobbyallen.uk@gmail.com) 
 */
class module_controller {

    /**
     * @var type 
     */
    static $inprocess = false;

    /**
     * 
     */
    static $type = null;

    /**
     * Carry out the power cycle request.
     */
    static function doPowerRequest() {
        if (!$_POST['inPlugType'] == "") {
            if ($_POST['inPlugType'] == "off") {
                self::$type = "shutdown and power-off";
            } else {
                self::$type = "restart (reboot)";
            }
            if (sys_versions::ShowOSPlatformVersion() == 'Windows') {
                $shutdown = "shutdown -s -f -t 10 -c \"Powerplug module for ZPanel requested a shutdown.\"";
                $restart = "shutdown -r -f -t 10 -c \"Powerplug module for ZPanel requested a restart.\"";
            } else {
                $shutdown = ctrl_options::GetOption('zsudo') . " shutdown -h -t 10";
                $restart = ctrl_options::GetOption('zsudo') . " shutdown -r -t 10";
            }
            if ($_POST['inPlugType'] == "off") {
                $command_to_run = $shutdown;
            } else {
                $command_to_run = $restart;
            }
            self::$inprocess = true;
            exec($command_to_run);
            return true;
        }
    }

    static function getResult() {
        if (self::$inprocess) {
            return ui_sysmessage::shout(ui_language::translate("The server is now going to " . self::$type . ", this process will start in 10 seconds!"), "zannounceok");
        }
    }

    function getDescription() {
        return ui_module::GetModuleDescription();
    }

    static

    function getModuleName() {
        $module_name = ui_module::GetModuleName();
        return $module_name;
    }

    static

    function getModuleIcon() {
        global $controller;
        $module_icon = "modules/" . $controller->GetControllerRequest('URL', 'module') . "/assets/icon.png";
        return $module_icon;
    }

}

?>
 <?php

class BMEcat_Plugin extends Pimcore_API_Plugin_Abstract implements Pimcore_API_Plugin_Interface {

    public static function needsReloadAfterInstall() {
        return true;
    }

    public static function install() {
        // we need a simple way to indicate that the plugin is installed, so we'll create a directory

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // NOTE - make sure that your plugin/MyPlugin directory is writable by whatever user Apache runs as //
        //////////////////////////////////////////////////////////////////////////////////////////////////////

        $path = self::getInstallPath();

        if(!is_dir($path)) {
            mkdir($path);
        }

        if (self::isInstalled()) {
            return "BMEcat Plugin successfully installed.";
        } else {
            return "BMEcat Plugin could not be installed";
        }
    }

    public static function uninstall() {
        rmdir(self::getInstallPath());

        if (!self::isInstalled()) {
            return "BMEcat Plugin successfully uninstalled.";
        } else {
            return "BMEcat Plugin could not be uninstalled";
        }
    }

    public static function isInstalled() {
        return is_dir(self::getInstallPath());
    }

    public static function getTranslationFile($language) {

    }

    public static function getInstallPath() {
        return PIMCORE_PLUGINS_PATH."/BMEcat/install";
    }

}

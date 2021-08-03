<?php

declare(strict_types = 1);

namespace Zinkil\KillStreaks;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use Zinkil\KillStreaks\Handlers\DatabaseHandler;
use Zinkil\KillStreaks\Listeners\EventListener;

class Loader extends PluginBase{

    private static $instance;
    private static $databaseHandler;

    public function onEnable() : void{

        self::$instance = $this;

        $this->setHandlers();
        $this->setListeners();
        $this->db = @mkdir($this->getDataFolder() . "killstreaks.db");
        $this->main = new\SQLite3($this->getDataFolder() . "killstreaks.db");
        $this->main = new\SQLite3($this->getDataFolder() . "killstreaks.db");

        $this->main->exec("CREATE TABLE IF NOT EXISTS playerstats (player TEXT PRIMARY KEY, killstreak INT, bestkillstreak INT);");
    }

    public static function getInstance() : Loader{
        return self::$instance;
    }

    public static function getDatabaseHandler() : DatabaseHandler{
        return self::$databaseHandler;
    }

    public static function isPlayer($player) : bool{
        return !is_null(self::getPlayer($player));
    }

    public static function getPlayer($info){
        $result = null;
        $player = self::getPlayerName($info);
        if ($player === null) {
            return $result;
            return;
        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof Player) {
            $result = $player;
        }
        return $result;
    }


    public static function getPlayerName($player){
        $result = null;
        if (isset($player) and !is_null($player)) {
            if ($player instanceof Player) {
                $result = $player->getName();
            } elseif (is_string($player)) {
                $result = $player;
            }
        }
        return $result;
    }

    public function setListeners(){
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

    public function setHandlers(){
        self::$databaseHandler = new DatabaseHandler();
    }
}
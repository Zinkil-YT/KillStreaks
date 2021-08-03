<?php

declare(strict_types = 1);

namespace Zinkil\KillStreaks\Handlers;

use pocketmine\Player;
use pocketmine\Server;
use Zinkil\KillStreaks\Loader;

class DatabaseHandler{

    private $plugin;

    public function __construct(){
        $this->plugin = Loader::getInstance();
    }

    public function playerStatsAdd($player){
        $check = $this->plugin->main->query("SELECT player FROM playerstats WHERE player='".Loader::getPlayerName($player)."';");
        $result = $check->fetchArray(SQLITE3_ASSOC);
        if(empty($result)){
            $query = $this->plugin->main->prepare("INSERT OR REPLACE INTO playerstats (player, killstreak, bestkillstreak) VALUES (:player, :killstreak, :bestkillstreak);");
            $query->bindValue(":player", $player);
            $query->bindValue(":killstreak", 0);
            $query->bindValue(":bestkillstreak", 0);
            $query->execute();
        }
    }

    public function getKillstreak($player){
        $query = $this->plugin->main->query("SELECT killstreak FROM playerstats WHERE player='".Loader::getPlayerName($player)."';");
        $result = $query->fetchArray(SQLITE3_ASSOC);
        return (int) $result["killstreak"];
    }

    public function getBestKillstreak($player){
        $query = $this->plugin->main->query("SELECT bestkillstreak FROM playerstats WHERE player='".Loader::getPlayerName($player)."';");
        $result = $query->fetchArray(SQLITE3_ASSOC);
        return (int) $result["bestkillstreak"];
    }

    public function setKillstreak($player, $int){
        $this->plugin->main->exec("UPDATE playerstats SET killstreak='$int' WHERE player='".Loader::getPlayerName($player)."';");
    }

    public function setBestKillstreak($player, $int){
        $this->plugin->main->exec("UPDATE playerstats SET bestkillstreak='$int' WHERE player='".Loader::getPlayerName($player)."';");
    }
}

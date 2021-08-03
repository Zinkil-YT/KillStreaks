<?php

declare(strict_types = 1);

namespace Zinkil\KillStreaks;

use pocketmine\Player;
use pocketmine\Server;
use Zinkil\KillStreaks\Loader;

class Utils{

    public $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    public static function updateStats($player, int $reason){
        switch($reason){
            case 0:
            $oplayer = Loader::getPlayer($player);
            $killstreak = Loader::getInstance()->getDatabaseHandler()->getKillstreak($player);
            Loader::getInstance()->getDatabaseHandler()->setKillstreak($player, $killstreak + 1);
            $bestkillstreak = Loader::getInstance()->getDatabaseHandler()->getBestKillstreak($player);
            $newkillstreak = Loader::getInstance()->getDatabaseHandler()->getKillstreak($player);
            if($newkillstreak >= $bestkillstreak){
                Loader::getInstance()->getDatabaseHandler()->setBestKillstreak($player, $newkillstreak);
            }
            if(!is_null($oplayer)) $oplayer->sendMessage("§aYou are on a killstreak of ".$newkillstreak." §7(".$bestkillstreak.")");
            break;
            case 1:
            $oplayer = Loader::getPlayer($player);
            $killstreak = Loader::getInstance()->getDatabaseHandler()->getKillstreak($player);
            $bestkillstreak = Loader::getInstance()->getDatabaseHandler()->getBestKillstreak($player);
            Loader::getInstance()->getDatabaseHandler()->setKillstreak($player, 0);
            if(!is_null($oplayer) and $killstreak > 0){
              $oplayer->sendMessage("§cYou lost your killstreak of ".$killstreak." §7(".$bestkillstreak.")");
            }
            break;
            case 2:
            Loader::getInstance()->getDatabaseHandler()->setKillstreak($player, 0);
            break;
            default:
            return;
        }
    }
}
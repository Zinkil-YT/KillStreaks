<?php

declare(strict_types = 1);

namespace Zinkil\KillStreaks\Listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use Zinkil\KillStreaks\Utils;
use Zinkil\KillStreaks\Loader;
use Zinkil\KillStreaks\CustomPlayer;

class EventListener implements Listener{

    public $plugin;
    
    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
    }

    function onCreation(PlayerCreationEvent $event){
        $event->setPlayerClass(CustomPlayer::class);
    }

    public function onPreLogin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();
        if($player instanceof CustomPlayer){
            $player->initializeLogin();
        }else{
            $this->plugin->getDatabaseHandler()->playerStatsAdd(Loader::getPlayerName($player));
        }
    }

	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$cause = $player->getLastDamageCause();
		if($cause instanceof EntityDamageByEntityEvent and $cause->getDamager() !== null){
			$killer = $cause->getDamager();
			if($player instanceof Player and $killer instanceof Player){
				if($killer instanceof CustomPlayer) Utils::updateStats($killer, 0);
				if($player instanceof CustomPlayer) Utils::updateStats($player, 1);
                if(Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) >= 5 and Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) < 10){
                    $this->plugin->getServer()->broadcastMessage("§l§eKillStreak » §r§b".$killer->getDisplayName()." §7just got a killstreak of §b".Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer));
                }
                if(Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) >= 10 and Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) < 20){
                    $this->plugin->getServer()->broadcastMessage("§l§eKillStreak » §r§a".$killer->getDisplayName()." §7just got a killstreak of §a".Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer));
                }
                if(Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) >= 20 and Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) < 30){
                    $this->plugin->getServer()->broadcastMessage("§l§eKillStreak » §r§6".$killer->getDisplayName()." §7just got a killstreak of §6".Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer));
                }
                if(Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) >= 30 and Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) < 50){
                    $this->plugin->getServer()->broadcastMessage("§l§eKillStreak » §r§c".$killer->getDisplayName()." §7just got a killstreak of §c".Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer));
                }
                if(Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) >= 50 and Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) < 75){
                    $this->plugin->getServer()->broadcastMessage("§l§eKillStreak » §r§d".$killer->getDisplayName()." §7just got a killstreak of §d".Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer));
                }
                if(Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer) >= 100){
                    $this->plugin->getServer()->broadcastMessage("§l§eKillStreak » §r§0".$killer->getDisplayName()." §7just got a killstreak of §0".Loader::getInstance()->getDatabaseHandler()->getKillstreak($killer));
                }
			}
		}
	}
}
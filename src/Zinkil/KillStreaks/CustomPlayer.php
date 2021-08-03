<?php

declare(strict_types = 1);

namespace Zinkil\KillStreaks;

use pocketmine\Player;
use pocketmine\Server;
use Zinkil\KillStreaks\Loader;
use Zinkil\KillStreaks\Handlers\DatabaseHandler;

class CustomPlayer extends Player{

  public function initializeLogin(){
    Loader::getInstance()->getDatabaseHandler()->playerStatsAdd(Loader::getPlayerName($this));
  }
}
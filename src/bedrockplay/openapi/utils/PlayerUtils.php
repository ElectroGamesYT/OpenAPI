<?php

declare(strict_types=1);

namespace bedrockplay\openapi\utils;

use bedrockplay\openapi\ranks\RankDatabase;
use pocketmine\Player;

/**
 * Trait PlayerUtils
 * @package bedrockplay\openapi\utils
 */
trait PlayerUtils {

    /**
     * @param Player $player
     * @param string $color
     */
    public function updateNameTag(Player $player, string $color = "§7") {
        $player->setNameTag(RankDatabase::getPlayerRank($player)->getFormatForNameTag() . $color . $player->getName() . "\n§5" . DeviceData::getDeviceName($player));
    }

    /**
     * @param Player $player
     * @param bool $heal
     * @param bool $clearInventory
     * @param bool $removeEffects
     * @param bool $denyFly
     */
    public function resetPlayer(Player $player, bool $heal = true, bool $clearInventory = true, bool $removeEffects = true, bool $denyFly = true) {
        if($heal) {
            $this->healPlayer($player);
        }
        if($clearInventory) {
            $this->clearPlayerInventory($player);
        }
        if($removeEffects) {
            $player->removeAllEffects();
        }
        if($denyFly) {
            $player->setFlying(false);
            $player->setAllowFlight(false);
        }

        $player->extinguish();
    }

    /**
     * @param Player $player
     */
    public function healPlayer(Player $player) {
        $player->setHealth(20);
        $player->setFood(20);
    }

    /**
     * @param Player $player
     */
    public function clearPlayerInventory(Player $player) {
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getCursorInventory()->clearAll();
    }
}
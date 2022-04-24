<?php

namespace BanSystem\command\kick;

use BanSystem\BanSystem;
use BanSystem\manager\notify\NotifyManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\Server;

class KickCommand extends Command {

    public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("kick.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender->hasPermission($this->getPermission())) {
            if (count($args) == 0) {
                $sender->sendMessage(BanSystem::getPrefix() . "§c/kick <player> <reason>");
                return false;
            }

            $player = array_shift($args);
            $reason = trim(implode(" ", $args));

            if (($player = Server::getInstance()->getPlayerByPrefix($player)) !== null) {
                $sender->sendMessage(BanSystem::getPrefix() . "§7The player §e" . $player->getName() . " §7was kicked!");
                $kickScreen = "§8» §cYou were kicked! §8«";
                $kickScreen .= "\n§8» §cReason: §e" . $reason;
                $player->kick($kickScreen);

                NotifyManager::sendNotify(
                    BanSystem::getPrefix() . "§e" . $player->getName() . " §7was kicked by §c" . $sender->getName() . "§7!\n" .
                    BanSystem::getPrefix() . "§7Reason: §e" . $reason
                );
            } else {
                $sender->sendMessage(BanSystem::getPrefix() . "§7The player §e" . $args[0] . " §7isn't online!");
            }
        } else {
            $sender->sendMessage(BanSystem::getPrefix() . BanSystem::NO_PERMS);
        }
        return true;
    }
}
<?php

namespace ShockedPlot7560\FactionMaster\Command\Subcommand;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use ShockedPlot7560\FactionMaster\API\MainAPI;
use ShockedPlot7560\FactionMaster\Utils\Ids;

class ClaimCommand extends BaseSubCommand {

    protected function prepare(): void {}

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) return;
        $permissions = MainAPI::getMemberPermission($sender->getName());
        $UserEntity = MainAPI::getUser($sender->getName());
        if ($permissions === null) {
            $sender->sendMessage(" §c>> §4You need to be in a faction to use that");
            return;
        }
        if ((isset($permissions[Ids::PERMISSION_ADD_CLAIM]) && $permissions[Ids::PERMISSION_ADD_CLAIM]) || $UserEntity->rank == Ids::OWNER_ID) {
            $Player = $sender->getPlayer();
            $Chunk = $Player->getLevel()->getChunkAtPosition($Player);
            $X = $Chunk->getX();
            $Z = $Chunk->getZ();
            $World = $Player->getLevel()->getName();

            $FactionClaim = MainAPI::getFactionClaim($World, $X, $Z);
            if ($FactionClaim === null) {
                if (MainAPI::addClaim($sender->getPlayer(), $UserEntity->faction)) {
                    $sender->sendMessage(" §a>> §2Chunk successfully claimed !");
                    return;
                }else{
                    $sender->sendMessage(" §c>> §4An error has occured");
                    return;
                }
            }else{
                $sender->sendMessage(" §c>> §4Chunk already claimed");
                return;
            }
        }else{
            $sender->sendMessage(" §c>> §4You don't have the permission to use these command");
            return;
        }
    }

}
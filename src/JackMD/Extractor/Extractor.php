<?php
declare(strict_types = 1);

namespace JackMD\Extractor;

use DevTools\DevTools;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use function strtolower;

class Extractor extends PluginBase{

	/**
	 * @param CommandSender $sender
	 * @param Command       $command
	 * @param string        $label
	 * @param array         $args
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
		switch(strtolower($command->getName())){
			case "extractall":
				if(!$sender->hasPermission("extractor.extractall")){
					$sender->sendMessage("No perm.");

					return false;
				}

				$this->extractAll($sender);
			break;
		}

		return true;
	}

	/**
	 * @param CommandSender $sender
	 */
	public function extractAll(CommandSender $sender): void{
		$sender->sendMessage("Extracting");

		$devtools = $this->getServer()->getPluginManager()->getPlugin("DevTools");

		if(!$devtools instanceof DevTools){
			return;
		}

		foreach($this->getServer()->getPluginManager()->getPlugins() as $plugin){
			$pluginName = $plugin->getName();

			if($pluginName === $this->getName() || $pluginName === "DevTools"){
				continue;
			}

			$command = "extractplugin " . $pluginName;

			$this->getServer()->dispatchCommand($sender, $command);
		}
	}
}
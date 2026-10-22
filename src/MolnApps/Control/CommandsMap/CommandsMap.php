<?php

namespace MolnApps\Control\CommandsMap;

class CommandsMap
{
	private $commandMap = [];
	private $viewMap = [];
	private $forwardMap = [];
	private $classrootMap = [];
	private $middlewareMap = [];
	
	public function addCommand(string $command)
	{
		$this->commandMap[$command] = 1;
	}

	public function getCommands()
	{
		return $this->commandMap;
	}
	
	public function verifyCommand(string $command)
	{
		return (isset($this->commandMap[$command]));
	}
	
	public function addClassroot(string $command, string $classroot)
	{
		if ($classroot) {
			$this->classrootMap[$command] = $classroot;
		}
	}
	
	public function getClassroot(string $command)
	{
		if (isset($this->classrootMap[$command])) {
			return $this->classrootMap[$command];
		}
		return $command;
	}
	
	public function addView(string $command, int $status, $view)
	{
		if ($view) {
			$this->viewMap[$command][$status][] = $view;
		}
	}

	public function setView(string $command, int $status, $view)
	{
		$this->viewMap[$command][$status] = [];
		$this->addView($command, $status, $view);
	}
	
	public function getViews(string $command, int $status) 
	{
		if (isset($this->viewMap[$command][$status])) {
			return $this->viewMap[$command][$status];
		}
	}
	
	public function addForward(string $command, int $status, $newCommand)
	{
		if ($newCommand) {
			$this->forwardMap[$command][$status] = $newCommand;
		}
	}

	public function setForward(string $command, int $status, $newCommand)
	{
		return $this->addForward($command, $status, $newCommand);
	}
	
	public function getForward(string $command, int $status)
	{
		if (isset($this->forwardMap[$command][$status])) {
			return $this->forwardMap[$command][$status];
		}
	}

	public function addMiddleware(string $command, array|string $middleware)
	{
		foreach ((array)$middleware as $m) {
			$this->middlewareMap[$command][] = $m;
		}
	}

	public function getMiddleware(string $command)
	{
		if (isset($this->middlewareMap[$command])) {
			return $this->middlewareMap[$command];
		}
		return [];
	}

	public function resolveCommand(string $command)
	{
		$command = $this->getClassroot($command);
		
		if ( ! $this->verifyCommand($command)) {
			throw new \Exception('Command '.$command.' is not defined.');
		}

		return $command;
	}
	
	public function __toString() {
		return implode(', ', array_keys($this->commandMap));
	}
}
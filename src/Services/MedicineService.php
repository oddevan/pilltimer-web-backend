<?php

namespace oddEvan\PillTimer\Services;

use Cavatappi\Foundation\Command\CommandHandler;
use Cavatappi\Foundation\Command\CommandHandlerService;
use Cavatappi\Foundation\Exceptions\ActionNotAuthorized;
use Cavatappi\Foundation\Exceptions\InvalidValueProperties;
use Cavatappi\Foundation\Factories\UuidFactory;
use oddEvan\PillTimer\Commands\AddMedicine;
use oddEvan\PillTimer\Events\MedicineAdded;
use Psr\EventDispatcher\EventDispatcherInterface;

class MedicineService implements CommandHandlerService {
	public function __construct(
		private MedicineRepo $repo,
		private EventDispatcherInterface $eventBus
	) {
	}

	#[CommandHandler]
	public function addMedicine(AddMedicine $cmd): void {
		if ($this->repo->has($cmd->medicine->id)) {
			throw new InvalidValueProperties("A medicine with the ID {$cmd->medicine->id} already exists");
		}
		if (!$cmd->userId->equals($cmd->medicine->userId)) {
			throw new ActionNotAuthorized('You cannot create a Medicine for someone else.');
		}

		$this->eventBus->dispatch(new MedicineAdded(
			medicine: $cmd->medicine,
			userId: $cmd->userId,
		));
	}
}

<?php

namespace oddEvan\PillTimer\Commands;

use Cavatappi\Foundation\Command\Authenticated;
use Cavatappi\Foundation\Command\Command;
use Cavatappi\Foundation\Value\ValueKit;
use DateTimeInterface;
use oddEvan\PillTimer\Entities\Dose;
use Ramsey\Uuid\UuidInterface;

readonly class AddDose implements Command, Authenticated {
	use ValueKit;

	public function __construct(
		public Dose $dose,
		public UuidInterface $userId,
	) {
	}
}

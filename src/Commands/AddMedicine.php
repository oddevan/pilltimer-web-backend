<?php

namespace oddEvan\PillTimer\Commands;

use Cavatappi\Foundation\Command\Authenticated;
use Cavatappi\Foundation\Command\Command;
use Cavatappi\Foundation\Value\ValueKit;
use oddEvan\PillTimer\Entities\Medicine;
use Ramsey\Uuid\UuidInterface;

class AddMedicine implements Command, Authenticated {
	use ValueKit;

	public function __construct(
		public readonly Medicine $medicine,
		public readonly UuidInterface $userId,
	) {
	}
}

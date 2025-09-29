<?php

namespace oddEvan\PillTimer\Entities;

use Cavatappi\Foundation\DomainEvent\Entity;
use Cavatappi\Foundation\Value;
use Cavatappi\Foundation\Value\ValueKit;
use Ramsey\Uuid\UuidInterface;

readonly class User implements Value, Entity {
	use ValueKit;

	public function __construct(
		public UuidInterface $id,
	) {
	}
}

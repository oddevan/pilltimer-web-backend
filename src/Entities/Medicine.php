<?php

namespace oddEvan\PillTimer\Entities;

use Cavatappi\Foundation\DomainEvent\Entity;
use Cavatappi\Foundation\Exceptions\InvalidValueProperties;
use Cavatappi\Foundation\Factories\UuidFactory;
use Cavatappi\Foundation\Validation\Validated;
use Cavatappi\Foundation\Value;
use Cavatappi\Foundation\Value\ValueKit;
use Ramsey\Uuid\UuidInterface;

readonly class Medicine implements Value, Entity, Validated {
	use ValueKit;

	public UuidInterface $id;

	public function __construct(
		public string $name,
		public UuidInterface $userId,
		?UuidInterface $id = null,
		public ?int $hourlyInterval = null,
		public ?int $dailyLimit = null,
		public bool $alert = false,
		public bool $archived = false,
	) {
		$this->id = $id ?? UuidFactory::random();
		$this->validate();
	}

	public function validate(): void {
		if (isset($this->hourlyInterval) && $this->hourlyInterval <= 0) {
			throw new InvalidValueProperties('Hourly interval must be null or positive.', field: 'hourlyInterval');
		}
		if (isset($this->dailyLimit) && $this->dailyLimit <= 0) {
			throw new InvalidValueProperties('Hourly interval must be null or positive.', field: 'dailyLimit');
		}
	}
}

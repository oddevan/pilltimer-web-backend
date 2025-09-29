<?php

namespace oddEvan\PillTimer\Events;

use Cavatappi\Foundation\DomainEvent\DomainEvent;
use Cavatappi\Foundation\Factories\UuidFactory;
use DateTimeImmutable;
use DateTimeInterface;
use oddEvan\PillTimer\Entities\Medicine;
use Ramsey\Uuid\UuidInterface;

class MedicineAdded implements DomainEvent {
	public readonly UuidInterface $id;
	public readonly DateTimeInterface $timestamp;

	public function __construct(
		public readonly Medicine $medicine,
		public readonly UuidInterface $userId,
		?UuidInterface $id = null,
		?DateTimeInterface $timestamp = null,
		public readonly ?UuidInterface $processId = null,
	) {
		$this->timestamp = $timestamp ?? new DateTimeImmutable();
		$this->id = $id ?? UuidFactory::date($this->timestamp);
	}

	public string $type { get => self::class; }
	public UuidInterface $entityId { get => $this->medicine->id; }
	public UuidInterface $aggregateId { get => $this->medicine->id; }
}

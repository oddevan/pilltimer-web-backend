<?php

namespace oddEvan\PillTimer\Events;

use Cavatappi\Foundation\DomainEvent\DomainEvent;
use Cavatappi\Foundation\Factories\UuidFactory;
use Cavatappi\Foundation\Value\ValueKit;
use DateTimeImmutable;
use DateTimeInterface;
use oddEvan\PillTimer\Entities\Dose;
use Ramsey\Uuid\UuidInterface;

class DoseAdded implements DomainEvent, ChangesNextDoseTime
{
	use ValueKit;

	public readonly UuidInterface $id;
	public readonly DateTimeInterface $timestamp;

	public function __construct(
		public readonly Dose $dose,
		public readonly UuidInterface $userId,
		?UuidInterface $id = null,
		?DateTimeInterface $timestamp = null,
		public readonly ?UuidInterface $processId = null,
	) {
		$this->timestamp = $timestamp ?? new DateTimeImmutable();
		$this->id = $id ?? UuidFactory::date($this->timestamp);
	}

	public string $type { get => self::class; }
	public UuidInterface $entityId { get => $this->dose->id; }
	public UuidInterface $aggregateId { get => $this->dose->medicineId; }

	public function doseTime(): ?DateTimeInterface
	{
		return $this->dose->timestamp;
	}
}

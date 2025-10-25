<?php

namespace oddEvan\PillTimer\Events;

use Cavatappi\Foundation\DomainEvent\DomainEvent;
use DateTimeInterface;

interface ChangesNextDoseTime extends DomainEvent {
	public function doseTime(): ?DateTimeInterface;
}

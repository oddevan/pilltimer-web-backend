<?php

namespace oddEvan\PillTimer\Services;

use DateTimeInterface;
use oddEvan\PillTimer\Entities\Medicine;
use Ramsey\Uuid\UuidInterface;

interface MedicineRepo {
	public function has(UuidInterface $medicineId): bool;
	public function get(UuidInterface $medicineId): ?Medicine;
	public function setNextDose(UuidInterface $medicineId, DateTimeInterface $timestamp): void;
}

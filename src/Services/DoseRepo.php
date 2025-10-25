<?php

namespace oddEvan\PillTimer\Services;

use oddEvan\PillTimer\Entities\Dose;
use Ramsey\Uuid\UuidInterface;

interface DoseRepo {
	/** @return Dose[] */
	public function dosesForMedicineInLastDay(UuidInterface $medicineId): array;
}

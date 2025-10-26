<?php

namespace oddEvan\PillTimer\Test;

use Cavatappi\Test\ModelTest;
use oddEvan\PillTimer\PillTimerBackend;
use oddEvan\PillTimer\Services\DoseRepo;
use oddEvan\PillTimer\Services\MedicineRepo;
use PHPUnit\Framework\MockObject\MockObject;

abstract class PillTimerTest extends ModelTest {
	const INCLUDED_MODELS = [PillTimerBackend::class];

	protected DoseRepo & MockObject $doseRepo;
	protected MedicineRepo & MockObject $medRepo;

	protected function createMockServices(): array {
		$this->doseRepo = $this->createMock(DoseRepo::class);
		$this->medRepo = $this->createMock(MedicineRepo::class);

		return [
			...parent::createMockServices(),
			DoseRepo::class => fn() => $this->doseRepo,
			MedicineRepo::class => fn() => $this->medRepo,
		];
	}
}

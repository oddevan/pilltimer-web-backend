<?php

namespace oddEvan\PillTimer\Services;

use Cavatappi\Test\AppTest;
use DateTimeImmutable;
use oddEvan\PillTimer\Entities\Dose;
use oddEvan\PillTimer\Entities\Medicine;
use oddEvan\PillTimer\Events\DoseAdded;
use oddEvan\PillTimer\PillTimerBackend;
use PHPUnit\Framework\MockObject\MockObject;

final class NextDoseServiceTest extends AppTest {
	const INCLUDED_MODELS = [PillTimerBackend::class];

	protected DoseRepo & MockObject $doseRepo;
	protected MedicineRepo & MockObject $medRepo;

	protected function createMockServices(): array
	{
		$this->doseRepo = $this->createMock(DoseRepo::class);
		$this->medRepo = $this->createMock(MedicineRepo::class);

		return [
			...parent::createMockServices(),
			DoseRepo::class => fn() => $this->doseRepo,
			MedicineRepo::class => fn() => $this->medRepo,
		];
	}

	public function testItCalculatesTheNextDoseCorrectly() {
		$baseTimestamp = new DateTimeImmutable('@' . time(), timezone_open('America/New_York'));

		$medicine = new Medicine(
			name: 'Ibuprofen',
			userId: $this->randomId(),
			hourlyInterval: 4,
			dailyLimit: 3,
		);
		$this->medRepo->method('get')->with(medicineId: $medicine->id)->willReturn($medicine);

		$existingDoses = [];
		$this->doseRepo->method('dosesForMedicineInLastDay')->
			with(medicineId: $medicine->id)->willReturn($existingDoses);

		$newDose = new Dose(
			id: $this->randomId(),
			medicineId: $medicine->id,
			timestamp: $baseTimestamp,
		);

		$expected = $baseTimestamp->modify('+4 hours');
		$this->medRepo->expects($this->once())->method('setNextDose')->with($medicine->id, $expected);

		$this->app->dispatch(new DoseAdded(
			dose: $newDose,
			userId: $medicine->userId,
		));
	}
}
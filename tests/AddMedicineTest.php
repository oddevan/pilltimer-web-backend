<?php

namespace oddEvan\PillTimer\Commands;

use oddEvan\PillTimer\Entities\Medicine;
use oddEvan\PillTimer\Events\MedicineAdded;
use oddEvan\PillTimer\Test\PillTimerTest;

final class AddMedicineTest extends PillTimerTest {
	private Medicine $testMed;

	protected function setUp(): void {
		parent::setUp();

		$this->testMed = new Medicine(
			name: 'Acetaminophen',
			userId: $this->randomId(),
			hourlyInterval: 6,
			dailyLimit: 3,
		);
	}

	public function testItCreatesTheMedicine() {
		// Medicine does not already exist.
		$this->medRepo->method('has')->with($this->testMed->id)->willReturn(false);

		$expected = new MedicineAdded(
			medicine: $this->testMed,
			userId: $this->testMed->userId,
		);

		$this->expectEvent($expected);

		$this->app->execute(new AddMedicine(
			medicine: $this->testMed,
			userId: $this->testMed->userId,
		));
	}
}
<?php

namespace oddEvan\PillTimer\Services;

use Cavatappi\Foundation\DomainEvent\EventListenerService;
use Cavatappi\Foundation\DomainEvent\ProjectionListener;
use DateTimeImmutable;
use oddEvan\PillTimer\Events\ChangesNextDoseTime;

class NextDoseService implements EventListenerService {
	public function __construct(
		private MedicineRepo $medicineRepo,
		private DoseRepo $doseRepo
	) {
	}

	public const ONE_HOUR = 60 * 60;
	public const TWENTY_FOUR_HOURS = 24 * self::ONE_HOUR;

	#[ProjectionListener]
	public function recalculate(ChangesNextDoseTime $event) {
		$doseTime = $event->doseTime()?->getTimestamp() ?? null;
		if (isset($doseTime) && time() - $doseTime > self::TWENTY_FOUR_HOURS) {
			// A dose older than the last 24 hours was changed; we can safely ignore it.
			return;
		}

		$medicine = $this->medicineRepo->get($event->aggregateId);
		$doses = $this->doseRepo->dosesForMedicineInLastDay($event->aggregateId);
		usort($doses, fn($doseA, $doseB) => $doseA->timestamp->getTimestamp() - $doseB->timestamp->getTimestamp());

		if (empty($doses)) {
			// No existing doses; we can safely ignore the event.
			return;
		}

		$this->medicineRepo->setNextDose(
			medicineId: $event->aggregateId,
			timestamp: self::calculate($doses, $medicine->hourlyInterval, $medicine->dailyLimit),
		);
	}

	/**
	 * @param Dose[] $doses
	 * @param integer|null $interval
	 * @param integer|null $limit
	 * @return DateTimeImmutable
	 */
	private static function calculate(array $doses, ?int $interval, ?int $limit): DateTimeImmutable {
		$earliestTimestamp = 0;
		if (isset($limit) && count($doses) >= $limit) {
			$earliest = $doses[0]->timestamp->getTimestamp();
			$earliestTimestamp = $earliest + self::TWENTY_FOUR_HOURS;
		}

		$latestTimestamp = 0;
		if (isset($interval)) {
			$latest = $doses[array_key_last($doses)]->timestamp->getTimestamp();
			$latestTimestamp = $latest + ($interval * self::ONE_HOUR);
		}

		$nextTimestamp = ($earliestTimestamp > $latestTimestamp) ? $earliestTimestamp : $latestTimestamp;
		return new DateTimeImmutable('@' . $nextTimestamp);
	}
}

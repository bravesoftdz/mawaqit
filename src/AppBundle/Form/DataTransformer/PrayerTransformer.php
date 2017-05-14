<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class PrayerTransformer implements DataTransformerInterface {

    /**
     *
     * @var string 
     */
    private $type;

    public function __construct($type) {
        $this->type = $type;
    }

    public function transform($values) {
        if ($this->type === TimeType::class) {
            return [
                "fajr" => $values[0] !== "" ? new \DateTime($values[0]) : null,
                "zuhr" => $values[1] !== "" ? new \DateTime($values[1]) : null,
                "asr" => $values[2] !== "" ? new \DateTime($values[2]) : null,
                "maghrib" => $values[3] !== "" ? new \DateTime($values[3]) : null,
                "isha" => $values[4] !== "" ? new \DateTime($values[4]) : null
            ];
        }

        return [
            "fajr" => (int) $values[0],
            "zuhr" => (int) $values[1],
            "asr" => (int) $values[2],
            "maghrib" => (int) $values[3],
            "isha" => (int) $values[4]
        ];
    }

    public function reverseTransform($prayerIssue) {
        return $prayerIssue;
    }

}

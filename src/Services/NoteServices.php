<?php

    Namespace App\Services;

    Class NoteServices{
        public function calculateAverageByCoefficient($noteList, $coefficient) {
            $sum = 0;
            $totalCoefficient = 0;

            foreach ($noteList as $index => $note) {
                $sum += $note->getNote() * $coefficient[$note->getMatiere()->getId()];
                $totalCoefficient += $coefficient[$note->getMatiere()->getId()];
            }

            if ($totalCoefficient == 0) {
                return 0;
            }

            return round($sum / $totalCoefficient, 2);
        }

        public function calculateAverage($noteList) {
            $sum = 0;
            $totalCoefficient = 0;

            foreach ($noteList as $index => $note) {
                $sum += $note->getNote();
                $totalCoefficient += 1;
            }

            if ($totalCoefficient == 0) {
                return 0;
            }

            return round($sum / $totalCoefficient, 2);
        }
    }
?>
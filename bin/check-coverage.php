<?php

// Breaks the build if the line coverage is below a threshold.
// PHPUnit has no built-in option for this.
//
// Usage: php bin/check-coverage.php <clover.xml> <min. percentage>

declare(strict_types=1);

[$file, $threshold] = [$argv[1] ?? 'clover.xml', (float) ($argv[2] ?? 90)];

$metrics = simplexml_load_file($file)->project->metrics;
$total = (int) $metrics['statements'];
$covered = (int) $metrics['coveredstatements'];
$percentage = $total === 0 ? 0.0 : $covered / $total * 100;

printf("Line coverage: %.2f%% (%d of %d lines), required: %.2f%%\n", $percentage, $covered, $total, $threshold);

if ($percentage < $threshold) {
    fwrite(STDERR, "FAIL: coverage is below the threshold\n");
    exit(1);
}

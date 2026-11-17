<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$relations = DB::select("
    SELECT TABLE_NAME, COLUMN_NAME,
           REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM information_schema.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = DATABASE()
      AND REFERENCED_TABLE_NAME IS NOT NULL
");

foreach ($relations as $fk) {
    $table = $fk->TABLE_NAME;
    $parent = $fk->REFERENCED_TABLE_NAME;

    $count = DB::table($table)
        ->leftJoin(
            $parent,
            "$table.$fk->COLUMN_NAME",
            '=',
            "$parent.$fk->REFERENCED_COLUMN_NAME"
        )
        ->whereNotNull("$table.$fk->COLUMN_NAME")
        ->whereNull("$parent.$fk->REFERENCED_COLUMN_NAME")
        ->count();

    echo "$table.$fk->COLUMN_NAME => $count" . PHP_EOL;
}
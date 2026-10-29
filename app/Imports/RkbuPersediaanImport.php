<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RkbuPersediaanImport implements ToCollection
{
    public function collection(Collection $rows): void
    {
        // Rows are read by the controller so it can validate the whole file before saving.
    }
}

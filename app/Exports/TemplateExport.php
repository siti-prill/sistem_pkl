<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateExport implements FromArray, WithHeadings
{
    protected array $headings;

    public function __construct(array $headings)
    {
        $this->headings = $headings;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function array(): array
    {
        return [];
    }
}

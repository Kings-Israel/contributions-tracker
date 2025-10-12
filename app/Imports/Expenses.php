<?php

namespace App\Imports;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Expenses implements ToCollection, WithMapping, WithValidation, WithHeadingRow
{
    public function rules(): array
    {
        return [
            'date' => ['required'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function map($row): array
    {
        return [
            'date' => $this->importParseDate($row['date_ddmmyyyy'], 'Y-m-d'),
            'description' => $row['expense_typedescription'],
            'amount' => $row['amount'],
        ];
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            // Process each row
            Expense::create([
                'user_id' => auth()->id(),
                'month' => $row['date'],
                'expense_type' => $row['description'],
                'amount' => $row['amount'],
            ]);
        }
    }

    public function importParseDate($value, $end_format = 'Y-m-d')
    {
        // Handle Excel's numeric serialized date
        if (is_numeric($value)) {
            try {
                return Date::excelToDateTimeObject($value)->format($end_format);
            } catch (\Exception $e) {
                // Fall through
                info($e);
            }
        }

        // Try common date formats
        $formats = [
        'Y-m-d',
        'd/m/Y',
        'm/d/Y',
        'd-m-Y',
        'm-d-Y',
        'd M Y',
        'M d, Y',
        'd.m.Y',
        'Y/m/d',

        'Y-m-d H:i:s',
        'd/m/Y H:i:s',
        'm/d/Y H:i:s',
        'd-m-Y H:i:s',
        'm-d-Y H:i:s',
        'd M Y H:i:s',
        'M d, Y H:i:s',
        'd.m.Y H:i:s',
        'Y/m/d H:i:s',

        'Y-m-d H:i',
        'd/m/Y H:i',
        'm/d/Y H:i',
        'd-m-Y H:i',
        'm-d-Y H:i',
        'd M Y H:i',
        'M d, Y H:i',
        'd.m.Y H:i',
        'Y/m/d H:i',

        'Y-m-d H:i A',
        'd/m/Y H:i A',
        'm/d/Y H:i A',
        'd-m-Y H:i A',
        'm-d-Y H:i A',
        'd M Y H:i A',
        'M d, Y H:i A',
        'd.m.Y H:i A',
        'Y/m/d H:i A',
        ];

        foreach ($formats as $format) {
        try {
            return Carbon::createFromFormat($format, $value)->format($end_format);
        } catch (\Exception $e) {
            continue;
        }
        }

        // Fallback: Try Carbon’s flexible parser
        try {
        return Carbon::parse($value)->format($end_format);
        } catch (\Exception $e) {
        info($e);
        return null; // Or handle invalid date
        }
    }
}

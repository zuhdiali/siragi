<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;   // <-- 2. Import


class SheetTemplateHonorExport extends StringValueBinder implements WithHeadings, WithTitle
{

    public function headings(): array
    {
        return [
            'peserta_id',
            'nama_peserta',
            'kegiatan_id',
            'tipe_personil',
            'pcl_or_pml',
            'kec_tujuan',
            'nama_sls',
            'lampiran_tgl_mulai',
            'lampiran_tgl_selesai',
            'jml_sampel_pcl',
            'tipe_pengawas',
            'pengawas_id',
            'nama_pengawas',
        ];
    }


    public function title(): string
    {
        return 'Template upload';
    }
}

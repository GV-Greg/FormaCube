<?php

namespace App\Exports;

use App\Model\Formation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ParticipantsExport implements fromCollection,
                                    WithProperties,
                                    WithCustomStartCell,
                                    WithHeadings,
                                    WithMapping,
                                    WithDrawings,
                                    WithColumnWidths,
                                    WithStyles,
                                    WithEvents,
                                    WithTitle
{
    use Exportable;

    private $formation;
    private $nbreStagiaires;
    private $tableau;

    /**
     * @param Formation $formation
     */
    public function __construct(Formation $formation)
    {
        $this->formation = $formation;
        $this->nbreStagiaires = $this->formation->inscrits()->count();
        $this->tableau = 'A6:F' . ($this->nbreStagiaires + 6);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->formation->inscrits()->orderBy('nom', 'ASC')->get();
    }

    /**
     * @return string[]
     */
    public function properties(): array
    {
        return [
            'creator' => 'FormaCube',
            'lastModifiedBy' => 'FormaCube',
            'title' => $this->formation->nom . ' - Liste des participants',
            'description' => 'Liste des participants à la formation' . $this->formation->nom,
            'subject' => 'Formation',
            'keywords' => 'formation,liste de participants,excel',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ListeParticipants';
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A6';
    }

    /**
     * @param mixed $inscrit
     * @return array
     */
    public function map($inscrit): array
    {
        return [
            $inscrit->nom,
            $inscrit->prenom,
            $inscrit->rue . ' ' . $inscrit->numero . $inscrit->boite . ', ' . $inscrit->ville->code_postal . ' ' . $inscrit->ville->ville,
            '',
            $inscrit->email,
        ];
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'NOM',
            'PRENOM',
            'ADRESSE',
            'Nbre de KM depuis domicile',
            'Email',
        ];
    }

    /**
     * @return Drawing[]
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function drawings(): array
    {
        $formation = $this->formation->get()->first();

        $drawingLogoIf3n = new Drawing();
        $drawingLogoIf3n->setName('logo-FormaCube');
        $drawingLogoIf3n->setDescription('logo FormaCube');
        $drawingLogoIf3n->setPath(public_path("storage/images/logos/Logo_FormaCube-Noir-125x25.png"));
        $drawingLogoIf3n->setHeight(60);
        $drawingLogoIf3n->setCoordinates('B2');

        $drawings = [$drawingLogoIf3n];

        return $drawings;
    }

    /**
     * @return int[]
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 30,
            'D' => 20,
            'E' => 16,
            'F' => 40,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array[]
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            $this->tableau => [
                'alignment' => [
                    'wrapText' => true
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => 'medium',
                        'color' => ['ARGB' => '000000'],
                    ],
                    'inside' => [
                        'borderStyle' => 'thin',
                        'color' => ['ARGB' => '000000'],
                    ]
                ]
            ],
            6 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center'
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => 'medium',
                        'color' => ['ARGB' => '000000'],
                    ],
                ]
            ],
            'D3' => [
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => 'center',
                ],
            ]
        ];
    }

    /**
     * @return \Closure[]
     */
    public function registerEvents(): array
    {
        return array(
            BeforeSheet::class=> function(BeforeSheet $event) {
                $event->sheet->getDelegate()->mergeCells('D3:E3');
                $event->sheet->getDelegate()->setCellValue('D3', 'Liste des participants');
            },

            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setAutoFilter('A6:'.$event->sheet->getDelegate()->getHighestColumn().'6');
                $event->sheet->getDelegate()->getStyle('A6:'.$event->sheet->getDelegate()->getHighestColumn().'6')->getFill()->setFillType('solid')->getStartColor()->setARGB('317283');
                $event->sheet->getDelegate()->getStyle('A6:'.$event->sheet->getDelegate()->getHighestColumn().'6')->getFont()->getColor()->setARGB('ffffff');

                for($i=0; $i < $this->nbreStagiaires; $i++) {
                    if($i%2 !== 0) {
                        $line = 'A' . ($i+7) . ':F' . ($i+7);
                        $event->sheet->getDelegate()->getStyle($line)->getFill()->setFillType('solid')->getStartColor()->setARGB('c3d7de');
                    }
                }
            }
        );
    }
}

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

    public function __construct(Formation $formation)
    {
        $this->formation = $formation;
        $this->nbreStagiaires = $this->formation->inscrits()->count();
        $this->tableau = 'A6:F' . ($this->nbreStagiaires + 6);
    }

    public function collection()
    {
        return $this->formation->inscrits()->orderBy('nom', 'ASC')->get();
    }

    public function properties(): array
    {
        return [
            'creator' => 'Interface3.Namur',
            'lastModifiedBy' => 'Interface3.Namur',
            'title' => $this->formation->nom . ' - Liste des participants',
            'description' => 'Liste des participants à la formation' . $this->formation->nom,
            'subject' => 'Formation',
            'keywords' => 'formation,liste de participants,excel',
        ];
    }

    public function title(): string
    {
        return 'ListeParticipants';
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function map($inscrit): array
    {
        return [
            $inscrit->nom,
            $inscrit->prenom,
            $inscrit->rue . ' ' . $inscrit->numero . $inscrit->boite . ', ' . $inscrit->ville->code_postal . ' ' . $inscrit->ville->ville,
            '',
            '',
            $inscrit->email,
        ];
    }

    public function headings(): array
    {
        return [
            'NOM',
            'PRENOM',
            'ADRESSE',
            'Nbre de KM domicile à IF3N',
            'Droit à l\'image',
            'Email',
        ];
    }

    public function drawings(): array
    {
        $formation = $this->formation->pouvsub()->get()->first();

        $drawingLogoIf3n = new Drawing();
        $drawingLogoIf3n->setName('logo-if3n');
        $drawingLogoIf3n->setDescription('logo Interface3.Namur');
        $drawingLogoIf3n->setPath(public_path("images/Interface3-logo.png"));
        $drawingLogoIf3n->setHeight(60);
        $drawingLogoIf3n->setCoordinates('B2');

        $drawings = [$drawingLogoIf3n];

        if($formation->nom != 'Interface3.Namur') {
            $drawingLogoPouvSub = new Drawing();
            $drawingLogoPouvSub->setName('logo-pouvoir-subsidiant');
            $drawingLogoPouvSub->setDescription('logo Pouvoir Subsidiant');
            $drawingLogoPouvSub->setPath(public_path("images/logos/" . $formation->logo));
            $drawingLogoPouvSub->setHeight(60);
            $drawingLogoPouvSub->setCoordinates('F2');
            array_push($drawings, $drawingLogoPouvSub);
        }

        return $drawings;
    }

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

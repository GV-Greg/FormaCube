<?php

namespace App\Exports;

use App\Model\Formation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuiviExport implements FromCollection,
                                WithProperties,
                                WithTitle,
                                WithHeadings,
                                WithMapping,
                                WithColumnWidths,
                                WithColumnFormatting,
                                WithStyles
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
        $this->tableau = 'A1:N' . ($this->nbreStagiaires + 1);
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
            'creator' => 'Interface3.Namur',
            'lastModifiedBy' => 'Interface3.Namur',
            'title' => 'DOC_336 DE Cefora - Suivi données stagiaires - ' . $this->formation->nom,
            'description' => 'Suivi des stagiaires à la formation' . $this->formation->nom,
            'subject' => 'Formation',
            'keywords' => 'formation,suivi des stagiaires,cefora,excel',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Sélectionnés';
    }

    /**
     * @param mixed $inscrit
     * @return array
     */
    public function map($inscrit): array
    {
        return [
            $inscrit->genre === 'homme' ? 'Mr' : 'Mme',
            $inscrit->nom,
            $inscrit->prenom,
            $inscrit->rue . ' ' . $inscrit->numero . $inscrit->boite,
            $inscrit->ville->code_postal,
            $inscrit->ville->ville,
            $inscrit->age,
            $inscrit->date_naissance->format('d-m-Y'),
            $inscrit->num_national,
            $inscrit->tel,
            $inscrit->email,
            $inscrit->duree_chomage,
            $inscrit->diplome,
            $inscrit->source_info,
        ];
    }

    /**
     * @return string[]
     */
    public function headings(): array
    {
        return [
            'Mme/Mr',
            'Nom',
            'Prénom',
            'Adresse',
            'CP',
            'Ville',
            'Âge',
            'Date Naissance',
            'N-National',
            'Tél',
            'Email',
            'Durée chômage',
            'Diplôme',
            'Recrutement',
        ];
    }

    /**
     * @return int[]
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 17,
            'D' => 30,
            'E' => 7,
            'F' => 20,
            'G' => 5,
            'H' => 15,
            'I' => 15,
            'J' => 20,
            'K' => 30,
            'L' => 15,
            'M' => 25,
            'N' => 15,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_DATE_DMYMINUS,
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
                    ],
                    'inside' => [
                        'borderStyle' => 'thin',
                    ]
                ]
            ],
//            $sheet->setAllBorders('thin'),

            'E' => [
                'alignment' => [
                    'horizontal' => 'right',
                ],
            ],
            'E1' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
            'G' => [
                'alignment' => [
                    'horizontal' => 'right',
                ],
            ],
            'G1' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
            'H' => [
                'alignment' => [
                    'horizontal' => 'right',
                ],
            ],
            'H1' => [
                'alignment' => [
                    'horizontal' => 'left',
                ],
            ],
            'I' => [
                'alignment' => [
                    'horizontal' => 'center',
                ],
            ],
            1 => [
                'font' => [
                    'bold' => true,
                ],
            ]
        ];
    }

}

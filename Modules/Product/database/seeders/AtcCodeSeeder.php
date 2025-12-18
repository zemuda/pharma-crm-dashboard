<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Core\Enums\ActiveStatus;
use Modules\Product\Models\AtcCode;

class AtcCodeSeeder extends Seeder
{
    // TODO: https://www.youtube.com/watch?v=Osl4NgAXvRk
    /**
     * Run the database seeds.
     * TODO: https://www.youtube.com/watch?v=Osl4NgAXvRk
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('atc_codes')->truncate();

        // Level 1: Anatomical Main Groups (14 groups)
        $level1Groups = $this->getLevel1Groups();
        foreach ($level1Groups as $group) {
            $level1 = AtcCode::create($group);

            // Level 2: Therapeutic Subgroups for each level 1
            $level2Groups = $this->getLevel2Groups($level1->code);
            foreach ($level2Groups as $subgroup) {
                $level2 = AtcCode::create(array_merge($subgroup, [
                    'parent_id' => $level1->id
                ]));

                // Level 3: Pharmacological Subgroups
                $level3Groups = $this->getLevel3Groups($level1->code, $level2->code);
                foreach ($level3Groups as $pharmaGroup) {
                    $level3 = AtcCode::create(array_merge($pharmaGroup, [
                        'parent_id' => $level2->id
                    ]));

                    // Level 4: Chemical Subgroups
                    $level4Groups = $this->getLevel4Groups($level1->code, $level2->code, $level3->code);
                    foreach ($level4Groups as $chemicalGroup) {
                        $level4 = AtcCode::create(array_merge($chemicalGroup, [
                            'parent_id' => $level3->id
                        ]));

                        // Level 5: Chemical Substances
                        $level5Groups = $this->getLevel5Substances($level1->code, $level2->code, $level3->code, $level4->code);
                        foreach ($level5Groups as $substance) {
                            AtcCode::create(array_merge($substance, [
                                'parent_id' => $level4->id
                            ]));
                        }
                    }
                }
            }
        }

        // Rebuild the tree structure for adjacency list
        $this->rebuildTree();

        $this->command->info('ATC codes seeded successfully!');
        $this->command->info('Total ATC codes: ' . AtcCode::count());
    }

    /**
     * Get Level 1 Anatomical Main Groups
     */
    private function getLevel1Groups(): array
    {
        return [
            [
                'code' => 'A',
                'name' => 'Alimentary tract and metabolism',
                'level' => 1,
                'info' => 'Drugs for the alimentary tract and metabolism',
                'therapeutic_uses' => 'Gastrointestinal disorders, diabetes, vitamins',
                'status' => ActiveStatus::Active,
                'who_guidelines' => 'WHO Essential Medicines List - Gastrointestinal',
                'sort_order' => 1,
            ],
            [
                'code' => 'B',
                'name' => 'Blood and blood forming organs',
                'level' => 1,
                'info' => 'Drugs affecting blood and blood formation',
                'therapeutic_uses' => 'Anticoagulants, antiplatelets, hematinics',
                'status' => ActiveStatus::Active,
                'sort_order' => 2,
            ],
            [
                'code' => 'C',
                'name' => 'Cardiovascular system',
                'level' => 1,
                'info' => 'Drugs for cardiovascular diseases',
                'therapeutic_uses' => 'Hypertension, heart failure, arrhythmias',
                'status' => ActiveStatus::Active,
                'who_guidelines' => 'WHO Essential Medicines List - Cardiovascular',
                'sort_order' => 3,
            ],
            [
                'code' => 'D',
                'name' => 'Dermatologicals',
                'level' => 1,
                'info' => 'Drugs for skin conditions',
                'therapeutic_uses' => 'Dermatitis, psoriasis, acne',
                'status' => ActiveStatus::Active,
                'sort_order' => 4,
            ],
            [
                'code' => 'G',
                'name' => 'Genito-urinary system and sex hormones',
                'level' => 1,
                'info' => 'Drugs for genitourinary system and sex hormones',
                'therapeutic_uses' => 'Contraceptives, hormones, urinary disorders',
                'status' => ActiveStatus::Active,
                'sort_order' => 5,
            ],
            [
                'code' => 'H',
                'name' => 'Systemic hormonal preparations, excluding sex hormones and insulins',
                'level' => 1,
                'info' => 'Systemic hormonal preparations',
                'therapeutic_uses' => 'Corticosteroids, thyroid hormones',
                'status' => ActiveStatus::Active,
                'sort_order' => 6,
            ],
            [
                'code' => 'J',
                'name' => 'Anti-infectives for systemic use',
                'level' => 1,
                'info' => 'Anti-infective drugs',
                'therapeutic_uses' => 'Antibiotics, antivirals, antifungals',
                'status' => ActiveStatus::Active,
                'who_guidelines' => 'WHO Essential Medicines List - Anti-infectives',
                'sort_order' => 7,
            ],
            [
                'code' => 'L',
                'name' => 'Antineoplastic and immunomodulating agents',
                'level' => 1,
                'info' => 'Drugs for cancer and immune system',
                'therapeutic_uses' => 'Chemotherapy, immunomodulators',
                'status' => ActiveStatus::Active,
                'sort_order' => 8,
            ],
            [
                'code' => 'M',
                'name' => 'Musculo-skeletal system',
                'level' => 1,
                'info' => 'Drugs for musculoskeletal system',
                'therapeutic_uses' => 'NSAIDs, muscle relaxants, anti-gout',
                'status' => ActiveStatus::Active,
                'sort_order' => 9,
            ],
            [
                'code' => 'N',
                'name' => 'Nervous system',
                'level' => 1,
                'info' => 'Drugs for nervous system',
                'therapeutic_uses' => 'Psychotropics, analgesics, anesthetics',
                'status' => ActiveStatus::Active,
                'who_guidelines' => 'WHO Essential Medicines List - Neurology',
                'sort_order' => 10,
            ],
            [
                'code' => 'P',
                'name' => 'Antiparasitic products, insecticides and repellents',
                'level' => 1,
                'info' => 'Antiparasitic drugs',
                'therapeutic_uses' => 'Anthelmintics, antimalarials, insecticides',
                'status' => ActiveStatus::Active,
                'sort_order' => 11,
            ],
            [
                'code' => 'R',
                'name' => 'Respiratory system',
                'level' => 1,
                'info' => 'Drugs for respiratory system',
                'therapeutic_uses' => 'Bronchodilators, antihistamines, cough suppressants',
                'status' => ActiveStatus::Active,
                'sort_order' => 12,
            ],
            [
                'code' => 'S',
                'name' => 'Sensory organs',
                'level' => 1,
                'info' => 'Drugs for sensory organs',
                'therapeutic_uses' => 'Ophthalmologicals, otologicals',
                'status' => ActiveStatus::Active,
                'sort_order' => 13,
            ],
            [
                'code' => 'V',
                'name' => 'Various',
                'level' => 1,
                'info' => 'Miscellaneous drugs',
                'therapeutic_uses' => 'Contrast media, diagnostics, other therapeutic products',
                'status' => ActiveStatus::Active,
                'sort_order' => 14,
            ],
        ];
    }

    /**
     * Get Level 2 Therapeutic Subgroups
     */
    private function getLevel2Groups(string $level1Code): array
    {
        $groups = [
            'A' => [
                ['code' => 'A01', 'name' => 'Stomatological preparations', 'level' => 2],
                ['code' => 'A02', 'name' => 'Drugs for acid related disorders', 'level' => 2],
                ['code' => 'A03', 'name' => 'Drugs for functional gastrointestinal disorders', 'level' => 2],
                ['code' => 'A04', 'name' => 'Antiemetics and antinauseants', 'level' => 2],
                ['code' => 'A05', 'name' => 'Bile and liver therapy', 'level' => 2],
                ['code' => 'A06', 'name' => 'Drugs for constipation', 'level' => 2],
                ['code' => 'A07', 'name' => 'Antidiarrheals, intestinal anti-inflammatory/anti-infective agents', 'level' => 2],
                ['code' => 'A08', 'name' => 'Antiobesity preparations, excluding diet products', 'level' => 2],
                ['code' => 'A09', 'name' => 'Digestives, including enzymes', 'level' => 2],
                ['code' => 'A10', 'name' => 'Drugs used in diabetes', 'level' => 2],
                ['code' => 'A11', 'name' => 'Vitamins', 'level' => 2],
                ['code' => 'A12', 'name' => 'Mineral supplements', 'level' => 2],
                ['code' => 'A13', 'name' => 'Tonic agents', 'level' => 2],
                ['code' => 'A14', 'name' => 'Anabolic agents for systemic use', 'level' => 2],
                ['code' => 'A15', 'name' => 'Appetite stimulants', 'level' => 2],
                ['code' => 'A16', 'name' => 'Other alimentary tract and metabolism products', 'level' => 2],
            ],
            'C' => [
                ['code' => 'C01', 'name' => 'Cardiac therapy', 'level' => 2],
                ['code' => 'C02', 'name' => 'Antihypertensives', 'level' => 2],
                ['code' => 'C03', 'name' => 'Diuretics', 'level' => 2],
                ['code' => 'C04', 'name' => 'Peripheral vasodilators', 'level' => 2],
                ['code' => 'C05', 'name' => 'Vasoprotectives', 'level' => 2],
                ['code' => 'C07', 'name' => 'Beta blocking agents', 'level' => 2],
                ['code' => 'C08', 'name' => 'Calcium channel blockers', 'level' => 2],
                ['code' => 'C09', 'name' => 'Agents acting on the renin-angiotensin system', 'level' => 2],
                ['code' => 'C10', 'name' => 'Lipid modifying agents', 'level' => 2],
            ],
            'J' => [
                ['code' => 'J01', 'name' => 'Antibacterials for systemic use', 'level' => 2],
                ['code' => 'J02', 'name' => 'Antimycotics for systemic use', 'level' => 2],
                ['code' => 'J04', 'name' => 'Antimycobacterials', 'level' => 2],
                ['code' => 'J05', 'name' => 'Antivirals for systemic use', 'level' => 2],
                ['code' => 'J06', 'name' => 'Immune sera and immunoglobulins', 'level' => 2],
                ['code' => 'J07', 'name' => 'Vaccines', 'level' => 2],
            ],
            'N' => [
                ['code' => 'N01', 'name' => 'Anesthetics', 'level' => 2],
                ['code' => 'N02', 'name' => 'Analgesics', 'level' => 2],
                ['code' => 'N03', 'name' => 'Antiepileptics', 'level' => 2],
                ['code' => 'N04', 'name' => 'Anti-parkinson drugs', 'level' => 2],
                ['code' => 'N05', 'name' => 'Psycholeptics', 'level' => 2],
                ['code' => 'N06', 'name' => 'Psychoanaleptics', 'level' => 2],
                ['code' => 'N07', 'name' => 'Other nervous system drugs', 'level' => 2],
            ],
        ];

        return $groups[$level1Code] ?? [];
    }

    /**
     * Get Level 3 Pharmacological Subgroups
     */
    private function getLevel3Groups(string $level1Code, string $level2Code): array
    {
        // Example for A02 (Drugs for acid related disorders)
        if ($level1Code === 'A' && $level2Code === 'A02') {
            return [
                ['code' => 'A02A', 'name' => 'Antacids', 'level' => 3],
                ['code' => 'A02B', 'name' => 'Drugs for peptic ulcer and gastro-oesophageal reflux disease (GORD)', 'level' => 3],
                ['code' => 'A02X', 'name' => 'Other drugs for acid related disorders', 'level' => 3],
            ];
        }

        // Example for J01 (Antibacterials)
        if ($level1Code === 'J' && $level2Code === 'J01') {
            return [
                ['code' => 'J01A', 'name' => 'Tetracyclines', 'level' => 3],
                ['code' => 'J01B', 'name' => 'Amphenicols', 'level' => 3],
                ['code' => 'J01C', 'name' => 'Beta-lactam antibacterials, penicillins', 'level' => 3],
                ['code' => 'J01D', 'name' => 'Other beta-lactam antibacterials', 'level' => 3],
                ['code' => 'J01E', 'name' => 'Sulfonamides and trimethoprim', 'level' => 3],
                ['code' => 'J01F', 'name' => 'Macrolides, lincosamides and streptogramins', 'level' => 3],
                ['code' => 'J01G', 'name' => 'Aminoglycoside antibacterials', 'level' => 3],
                ['code' => 'J01M', 'name' => 'Quinolone antibacterials', 'level' => 3],
                ['code' => 'J01R', 'name' => 'Combinations of antibacterials', 'level' => 3],
                ['code' => 'J01X', 'name' => 'Other antibacterials', 'level' => 3],
            ];
        }

        // Add more level 3 groups as needed
        return [];
    }

    /**
     * Get Level 4 Chemical Subgroups
     */
    private function getLevel4Groups(string $level1Code, string $level2Code, string $level3Code): array
    {
        // Example for A02B (Drugs for peptic ulcer)
        if ($level1Code === 'A' && $level2Code === 'A02' && $level3Code === 'A02B') {
            return [
                ['code' => 'A02BA', 'name' => 'H2-receptor antagonists', 'level' => 4],
                ['code' => 'A02BB', 'name' => 'Prostaglandins', 'level' => 4],
                ['code' => 'A02BC', 'name' => 'Proton pump inhibitors', 'level' => 4],
                ['code' => 'A02BD', 'name' => 'Combinations for eradication of Helicobacter pylori', 'level' => 4],
                ['code' => 'A02BX', 'name' => 'Other drugs for peptic ulcer and GORD', 'level' => 4],
            ];
        }

        // Example for J01C (Penicillins)
        if ($level1Code === 'J' && $level2Code === 'J01' && $level3Code === 'J01C') {
            return [
                ['code' => 'J01CA', 'name' => 'Penicillins with extended spectrum', 'level' => 4],
                ['code' => 'J01CE', 'name' => 'Beta-lactamase sensitive penicillins', 'level' => 4],
                ['code' => 'J01CF', 'name' => 'Beta-lactamase resistant penicillins', 'level' => 4],
                ['code' => 'J01CG', 'name' => 'Beta-lactamase inhibitors', 'level' => 4],
                ['code' => 'J01CR', 'name' => 'Combinations of penicillins, incl. beta-lactamase inhibitors', 'level' => 4],
            ];
        }

        return [];
    }

    /**
     * Get Level 5 Chemical Substances
     */
    private function getLevel5Substances(string $level1Code, string $level2Code, string $level3Code, string $level4Code): array
    {
        // Example for A02BC (Proton pump inhibitors)
        if ($level1Code === 'A' && $level2Code === 'A02' && $level3Code === 'A02B' && $level4Code === 'A02BC') {
            return [
                [
                    'code' => 'A02BC01',
                    'name' => 'Omeprazole',
                    'level' => 5,
                    'info' => 'Proton pump inhibitor for acid suppression',
                    'therapeutic_uses' => 'GERD, peptic ulcers, Zollinger-Ellison syndrome',
                    'contraindications' => 'Hypersensitivity to omeprazole, severe liver impairment',
                    'status' => ActiveStatus::Active,
                    'meta' => [
                        'half_life' => '1 hour',
                        'protein_binding' => '95%',
                        'metabolism' => 'Hepatic (CYP2C19, CYP3A4)',
                        'excretion' => 'Renal (80%)',
                        'pregnancy_category' => 'C',
                    ],
                ],
                [
                    'code' => 'A02BC02',
                    'name' => 'Pantoprazole',
                    'level' => 5,
                    'info' => 'Proton pump inhibitor',
                    'therapeutic_uses' => 'GERD, erosive esophagitis, ulcers',
                    'contraindications' => 'Hypersensitivity to pantoprazole',
                    'status' => ActiveStatus::Active,
                    'meta' => [
                        'half_life' => '1 hour',
                        'protein_binding' => '98%',
                        'metabolism' => 'Hepatic (CYP2C19)',
                        'excretion' => 'Renal (71%)',
                    ],
                ],
                [
                    'code' => 'A02BC03',
                    'name' => 'Lansoprazole',
                    'level' => 5,
                    'info' => 'Proton pump inhibitor',
                    'therapeutic_uses' => 'GERD, ulcers, H. pylori eradication',
                    'contraindications' => 'Hypersensitivity to lansoprazole',
                    'status' => ActiveStatus::Active,
                ],
                [
                    'code' => 'A02BC04',
                    'name' => 'Rabeprazole',
                    'level' => 5,
                    'info' => 'Proton pump inhibitor',
                    'therapeutic_uses' => 'GERD, duodenal ulcers',
                    'contraindications' => 'Hypersensitivity to rabeprazole',
                    'status' => ActiveStatus::Active,
                ],
                [
                    'code' => 'A02BC05',
                    'name' => 'Esomeprazole',
                    'level' => 5,
                    'info' => 'S-isomer of omeprazole, proton pump inhibitor',
                    'therapeutic_uses' => 'GERD, erosive esophagitis, H. pylori eradication',
                    'contraindications' => 'Hypersensitivity to esomeprazole',
                    'status' => ActiveStatus::Active,
                ],
            ];
        }

        // Example for J01CA (Extended spectrum penicillins)
        if ($level1Code === 'J' && $level2Code === 'J01' && $level3Code === 'J01C' && $level4Code === 'J01CA') {
            return [
                [
                    'code' => 'J01CA01',
                    'name' => 'Ampicillin',
                    'level' => 5,
                    'info' => 'Aminopenicillin antibiotic',
                    'therapeutic_uses' => 'Respiratory tract infections, UTIs, meningitis',
                    'contraindications' => 'Hypersensitivity to penicillins',
                    'status' => ActiveStatus::Active,
                    'meta' => [
                        'spectrum' => 'Broad spectrum',
                        'administration' => 'Oral, IV, IM',
                        'pregnancy_category' => 'B',
                    ],
                ],
                [
                    'code' => 'J01CA02',
                    'name' => 'Pivampicillin',
                    'level' => 5,
                    'info' => 'Prodrug of ampicillin',
                    'therapeutic_uses' => 'Respiratory and urinary tract infections',
                    'contraindications' => 'Penicillin hypersensitivity',
                    'status' => ActiveStatus::Active,
                ],
                [
                    'code' => 'J01CA03',
                    'name' => 'Carbenicillin',
                    'level' => 5,
                    'info' => 'Carboxypenicillin antibiotic',
                    'therapeutic_uses' => 'Pseudomonas infections',
                    'contraindications' => 'Penicillin hypersensitivity',
                    'status' => ActiveStatus::Active,
                ],
                [
                    'code' => 'J01CA04',
                    'name' => 'Amoxicillin',
                    'level' => 5,
                    'info' => 'Aminopenicillin antibiotic',
                    'therapeutic_uses' => 'Broad range of bacterial infections',
                    'contraindications' => 'Penicillin hypersensitivity',
                    'status' => ActiveStatus::Active,
                    'meta' => [
                        'spectrum' => 'Broad spectrum',
                        'half_life' => '1-1.5 hours',
                        'excretion' => 'Renal',
                    ],
                ],
            ];
        }

        return [];
    }

    /**
     * Rebuild tree structure for adjacency list
     */
    private function rebuildTree(): void
    {
        // This method ensures the _lft and _rgt columns are properly set
        // The HasRecursiveRelationships trait should handle this automatically
        // when you use the tree methods, but you can also call:

        // If you want to manually rebuild:
        // AtcCode::fixTree();

        // Or create a root and rebuild from there
        $root = AtcCode::whereNull('parent_id')->first();
        if ($root) {
            AtcCode::rebuildSubtree($root);
        }
    }
}

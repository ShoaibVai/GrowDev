<?php

namespace Database\Seeders;

use App\Models\DocumentationTemplate;
use Illuminate\Database\Seeder;

class DocumentationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // IEEE SRS Template
        DocumentationTemplate::create([
            'name' => 'IEEE SRS Template',
            'type' => 'srs',
            'structure' => [
                'sections' => [
                    [
                        'id' => 'intro',
                        'title' => '1. Introduction',
                        'required' => true,
                        'subsections' => [
                            ['id' => 'purpose', 'title' => '1.1 Purpose', 'required' => true],
                            ['id' => 'scope', 'title' => '1.2 Scope', 'required' => true],
                            ['id' => 'definitions', 'title' => '1.3 Definitions, Acronyms, and Abbreviations', 'required' => false],
                            ['id' => 'references', 'title' => '1.4 References', 'required' => false],
                            ['id' => 'overview', 'title' => '1.5 Overview', 'required' => false],
                        ]
                    ],
                    [
                        'id' => 'overall_description',
                        'title' => '2. Overall Description',
                        'required' => true,
                        'subsections' => [
                            ['id' => 'product_perspective', 'title' => '2.1 Product Perspective', 'required' => true],
                            ['id' => 'product_functions', 'title' => '2.2 Product Functions', 'required' => true],
                            ['id' => 'user_characteristics', 'title' => '2.3 User Characteristics', 'required' => true],
                            ['id' => 'constraints', 'title' => '2.4 Constraints', 'required' => false],
                            ['id' => 'assumptions_dependencies', 'title' => '2.5 Assumptions and Dependencies', 'required' => false],
                        ]
                    ],
                    [
                        'id' => 'requirements',
                        'title' => '3. Requirements',
                        'required' => true,
                        'subsections' => [
                            ['id' => 'functional_requirements', 'title' => '3.1 Functional Requirements', 'required' => true],
                            ['id' => 'non_functional_requirements', 'title' => '3.2 Non-functional Requirements', 'required' => true],
                        ]
                    ],
                    [
                        'id' => 'verification',
                        'title' => '4. Verification',
                        'required' => true
                    ],
                    [
                        'id' => 'appendices',
                        'title' => '5. Appendices',
                        'required' => false
                    ],
                ],
                'requirements' => [
                    'functional' => ['id', 'description', 'input', 'processing', 'output', 'priority', 'notes'],
                    'non_functional' => ['type', 'criteria', 'measurement', 'priority', 'notes'],
                ]
            ]
        ]);

        // Agile SRS Template (simplified)
        DocumentationTemplate::create([
            'name' => 'Agile User Stories Template',
            'type' => 'srs',
            'structure' => [
                'sections' => [
                    [
                        'id' => 'vision',
                        'title' => 'Product Vision',
                        'required' => true
                    ],
                    [
                        'id' => 'user_stories',
                        'title' => 'User Stories',
                        'required' => true
                    ],
                    [
                        'id' => 'acceptance_criteria',
                        'title' => 'Acceptance Criteria',
                        'required' => true
                    ],
                ],
                'user_stories' => [
                    'fields' => ['title', 'description', 'acceptance_criteria', 'priority', 'story_points', 'epic']
                ]
            ]
        ]);
    }
}

<?php

return [
    'title' => 'Learn',
    'page_title' => 'Learn - Edelweiss Detection',

    'tab' => [
        'conditions' => 'Know the Conditions',
        'system' => 'About System',
    ],

    'conditions' => [
        'card_title' => 'Know the Health Conditions of Java Edelweiss Flower',
        'card_desc' => 'Java Edelweiss (Anaphalis javanica) is an endemic mountain flower of Indonesia that is protected. Understanding its health indicators helps conservation and cultivation efforts. Below are the health conditions the system can recognize.',
        'note' => 'The system will continue to be developed to recognize more Edelweiss health indicators in the future.',

        'mekar' => [
            'desc' => 'Healthy flower with open crown. Distinctive color is clearly visible, structure intact. Plant is in healthy condition.',
            'ciri1' => 'Fully open crown',
            'ciri2' => 'Distinctive cream-white color',
            'ciri3' => 'Intact flower structure',
        ],
        'sangat_mekar' => [
            'desc' => 'Flower at the peak of its health condition — large size, bright color. Sign of excellent growing environment.',
            'ciri1' => 'Maximum crown size',
            'ciri2' => 'Very bright color',
            'ciri3' => 'Most complete shape',
        ],
        'penyemaian' => [
            'desc' => 'Early growth phase from seed. Indicator of Edelweiss population regeneration in its natural habitat.',
            'ciri1' => 'Young green sprouts',
            'ciri2' => 'Roots beginning to grow',
            'ciri3' => 'Still small in size',
        ],
    ],

    'system' => [
        'card_title' => 'About This System',
        'card_desc' => 'Technical information about how this system detects Edelweiss flower health conditions, including model architecture, dataset used, and development tools.',

        'arch_title' => 'Model Architecture',
        'arch_approach' => 'Approach',
        'arch_approach_value' => 'Two Stage',
        'arch_stage1' => 'Stage 1',
        'arch_stage2' => 'Stage 2',
        'arch_input' => 'Input Resolution',
        'arch_conditions' => 'Conditions Recognized',
        'arch_conditions_value' => '3 conditions',

        'metrics_title' => 'Training Results',
        'metrics_yolo_map50' => 'YOLO mAP@0.5',
        'metrics_yolo_map5095' => 'YOLO mAP@0.5:0.95',
        'metrics_yolo_precision' => 'YOLO Precision',
        'metrics_yolo_recall' => 'YOLO Recall',
        'metrics_mlp_acc' => 'MLP Validation Accuracy',
        'metrics_optimizer' => 'Optimizer',

        'data_title' => 'Training Data',
        'data_source' => 'Data Source',
        'data_source_value' => 'Self-collected',
        'data_location' => 'Location',
        'data_location_value' => 'Mount Gede Pangrango',
        'data_annotation_tool' => 'Annotation Tool',
        'data_total' => 'Total Images',
        'data_train' => 'Training',
        'data_val' => 'Validation',
        'data_test' => 'Test',

        'tools_title' => 'Tools Used',
        'tools_training' => 'Training Platform',
        'tools_gpu' => 'GPU',
        'tools_ml_framework' => 'ML Framework',
        'tools_web' => 'Web Application',
        'tools_service' => 'Detection Service',
        'tools_db' => 'Database',

        'pipeline_title' => 'How the System Works',
        'pipeline_step1_title' => '1. Image Input',
        'pipeline_step1_desc' => 'Photo from upload or camera (any resolution)',
        'pipeline_step2_title' => '2. Finding Flowers',
        'pipeline_step2_desc' => 'YOLOv11 determines the position of each flower in the image',
        'pipeline_step3_title' => '3. Determining Health',
        'pipeline_step3_desc' => 'MLP classifies into 3 conditions: Blooming, Full Bloom, or Seedling',
        'pipeline_step4_title' => '4. Results Display',
        'pipeline_step4_desc' => 'Bounding boxes + condition labels + confidence scores',
    ],
];

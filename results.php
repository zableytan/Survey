<?php
require_once __DIR__ . '/config/db.php';

$areas = [
    'area2' => [
        'name' => 'Area 2: Quality Assurance',
        'table' => 'area2_responses',
        'questions' => [
            'A documented Internal Quality Assurance (IQA) plan is in place with clearly defined policies, procedures, and activities that enable the medical school to develop the quality of its academic and non-academic programs and processes.',
            'Stakeholders are involved in the formulation, implementation, and evaluation of the IQA plan.',
            'An IQA structure is established with roles, responsibilities, and accountabilities defined across all levels.',
            'Adequate resources are committed to supporting the IQA programs.',
            'Measurable targets and performance indicators are used to measure the performance of the medical school\'s IQA system.',
            'Mechanisms for communicating the performance results to concerned stakeholders are in place.',
            'The implementation of the IQA plan is reviewed annually for continuous improvement.',
            'The medical school has an established external quality assurance assessment plan to comply with institutional and regulatory requirements.',
            'The assessment is evidence-based and done by credible and independent external agencies.',
            'The results of the assessment are communicated to both internal and external stakeholders.',
            'The assessment results and findings are analyzed and used for improvement.',
            'The plans and activities for external quality assurance assessments are regularly improved.'
        ]
    ]
    // Add more areas here as you implement them
];

$selected_area = isset($_GET['area']) && isset($areas[$_GET['area']]) ? $_GET['area'] : 'area2';
$area = $areas[$selected_area];

// Define sub-areas for Area 2
$sub_areas = [];
if ($selected_area === 'area2') {
    $sub_areas = [
        [
            'title' => 'Sub-area 2.1. Internal Quality Assurance System',
            'questions' => range(1, 7)
        ],
        [
            'title' => 'Sub-area 2.2. External Quality Assurance',
            'questions' => range(8, 12)
        ]
    ];
}

// Query summary
$summary = [];
$total_responses = 0;
$sql = "SELECT COUNT(*) as cnt";
for ($i = 1; $i <= count($area['questions']); $i++) {
    $sql .= ", AVG(q$i) as avg_q$i";
}
$sql .= " FROM {$area['table']}";
$res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) {
    $total_responses = $row['cnt'];
    for ($i = 1; $i <= count($area['questions']); $i++) {
        $summary[$i] = [
            'avg' => is_null($row["avg_q$i"]) ? null : round($row["avg_q$i"], 2)
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Results</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f6f8fa; margin: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; padding: 32px 24px; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
        h2 { text-align: center; color: #2d3a4a; margin-bottom: 18px; }
        .area-select { text-align: center; margin-bottom: 30px; }
        select { padding: 8px 16px; border-radius: 6px; border: 1.5px solid #c3d2f7; font-size: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; background: #f8fafc; }
        th, td { padding: 12px 10px; text-align: left; }
        th { background: #eaf3ff; color: #2d6be6; font-weight: 600; }
        tr:nth-child(even) { background: #f0f4fa; }
        tr:nth-child(odd) { background: #f8fafc; }
        .summary { margin: 18px 0 0 0; color: #3b4a5a; font-size: 1.05rem; }
        .subarea-header td {
            background: #e0eaff !important;
            color: #2d6be6 !important;
            font-weight: 600;
            font-size: 1.05rem;
            border-top: 2px solid #b3cfff;
        }
        @media (max-width: 700px) {
            .container { padding: 10px 2vw; border-radius: 0; }
            th, td { font-size: 0.98rem; padding: 8px 4px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Survey Results</h2>
        <div class="area-select">
            <form method="get" action="">
                <label for="area">Select Survey Area: </label>
                <select name="area" id="area" onchange="this.form.submit()">
                    <?php foreach ($areas as $key => $a): ?>
                        <option value="<?= htmlspecialchars($key) ?>" <?= $key === $selected_area ? 'selected' : '' ?>><?= htmlspecialchars($a['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="summary">
            Total Responses: <strong><?= $total_responses ?></strong>
        </div>
        <table>
            <tr>
                <th>Question</th>
                <th>Average Rating</th>
            </tr>
            <?php if ($selected_area === 'area2'): ?>
                <?php foreach ($sub_areas as $sub_idx => $sub): ?>
                    <tr class="subarea-header">
                        <td colspan="2" style="background:#e0eaff; color:#2d6be6; font-weight:600; font-size:1.05rem; border-top:2px solid #b3cfff;">
                            <?= htmlspecialchars($sub['title']) ?>
                        </td>
                    </tr>
                    <?php foreach ($sub['questions'] as $j => $i): ?>
                        <tr>
                            <td>
                                <?php
                                    // Display question number as 1-based within sub-area only
                                    echo ($j + 1) . '. ' . htmlspecialchars($area['questions'][$i-1]);
                                ?>
                            </td>
                            <td><?= isset($summary[$i]['avg']) && $summary[$i]['avg'] !== null ? $summary[$i]['avg'] : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <?php foreach ($area['questions'] as $i => $q): ?>
                <tr>
                    <td><?= htmlspecialchars($q) ?></td>
                    <td><?= isset($summary[$i+1]['avg']) && $summary[$i+1]['avg'] !== null ? $summary[$i+1]['avg'] : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>

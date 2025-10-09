<?php
require_once __DIR__ . '/config/db.php';

$areas = [
    'area1' => [
        'name' => 'Area 1: Leadership and Governance',
        'table' => 'area1_responses',
        'questions' => [
            'The process of articulating the vision and mission statements was carried out involving the participation of the medical school\'s stakeholders.',
            'The vision and mission statements are communicated periodically to its stakeholders.',
            'All sectors of the medical school can identify with and own the vision-mission statements of the institution.',
            'The medical school defines clear indicators of how the vision and mission are achieved.',
            'There is a periodic revisiting of the medical school\'s vision and mission.',
            'The medical school has a clearly articulated mission statement that addresses the priority health care needs of the community, region, and nation.',
            'The Governing Board and the administrators are well qualified and have the experience to function in their respective roles.',
            'Management promotes good governance, promoting integrity and accountability.',
            'Leadership is open to suggestions and proactively anticipating and responding to changes that may affect the medical school\'s operations.',
            'Leadership training and succession planning are provided for.',
            'The medical school periodically undertakes a strategic planning process with the involvement of key stakeholders.',
            'The plans, programs, and activities are aligned with the medical school\'s vision, mission, and objectives.',
            'Relevant external and internal factor conditions are identified and used in the formulation of the plan.',
            'Plans, programs, and activities have clear and measurable targets and are time bound.',
            'A system for periodic follow-through and evaluation is in place for plans, programs, and activities.',
            'Ethics, social responsibility, technology, innovation, and internationalization are considered in formulating the strategic plan.',
            'Adequate resources are committed to the planning exercise and the implementation and evaluation of the strategic plan.',
            'A system following the Plan-Do-Check-Act (PDCA) cycle is followed in policy formulation and implementation.',
            'Policies and procedures promote the medical school\'s values and the unique culture of the institution.',
            'They are customer-focused and enforced with transparency, consistency, and fairness.',
            'They consider interrelationships among the various sectors of the medical school and promote synergy in operations.',
            'Policies for teaching-learning, research, community engagement, and services are articulated and documented.',
            'They comply with government regulations and standards.',
            'A risk management program is in place to assess, communicate and implement initiatives to identify and mitigate current and potential sources of risk.',
            'Explicit risk management policies and established protocols are defined to forestall any identified risks.',
            'Management assumes the primary responsibility for managing risks and involves the participation of key stakeholders in initiatives involving risk determination and control.',
            'Medical school resources are utilized effectively, safeguarded, and sufficiently ensured.',
            'Transparent monitoring processes are established so that all risk-mitigating efforts are working and are effective.'
        ]
    ],
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
    ],
    'area3' => [
        'name' => 'Area 3: Resource Management',
        'table' => 'area3_responses',
        'questions' => [
            'Human resource plans, policies, and programs are defined and implemented to enable the medical school to achieve its teaching, research, and community service functions.',
            'Recruitment, selection, and hiring policies are formulated and communicated and are consistently applied.',
            'Training and development programs are needs-based and are provided to employees.',
            'Deployment, promotion, succession, and career pathing programs are in place.',
            'A performance management system covering job evaluation, reward, recognition, coaching, and mentoring is in place.',
            'Salaries, incentives, and benefits are set at levels that ensure the medical school attracts and retains qualified staff.',
            'Provisions for resignation, termination, and retirement are in place.',
            'There is a sufficient workforce to attend to the needs of the medical school.',
            'The working environment is risk-free and safe for the employees.',
            'Policies and programs are in place to promote the well-being of employees.',
            'Human resource plans, policies, and programs are periodically assessed for improvement.',
            'The financial management system is designed to make resources available to support the medical school\'s vision, mission, and goals, particularly in teaching, research, and community service.',
            'There are adequate funds to guarantee the viability of medical school operations and programs, with provisions for good sourcing of finances when needed.',
            'A participative budgeting process is in place, which includes regular budget performance reports and analysis.',
            'Accounting internal controls function effectively to safeguard the assets, promote the integrity of the accounting records, and ensure compliance with regulatory requirements.',
            'Internal and external audits are regularly carried out to ensure the reliability of accounting systems and reports.',
            'Responsibilities for asset custody, use, control, and accountability are clearly defined.',
            'There is a facilities development plan with a sufficient budget that is documented and regularly updated.',
            'The plan reflects consideration for environmental responsibility in its programs.',
            'There is an office responsible for the development, maintenance, and safety of the physical facilities.',
            'Infrastructures are adequate and relevant to support teaching-learning, research, and community service.',
            'Classrooms, lecture halls, seminar rooms, and computer rooms are adequate, kept clean, free from distractions, and conducive for learning.',
            'Library and laboratories are adequate, accessible, up-to-date, and with a budget for developing collections.',
            'The IT facilities and infrastructure, both hardware and software, are adequate, up-to-date, and secure.',
            'There are sufficient provisions in the use of physical facilities to promote the health and safety of students and staff.',
            'The medical school is compliant with contractual and government requirements as to physical and IT facilities.',
            'Some facilities and provisions cater to people with special needs.'
        ],
        'sub_areas' => [
            ['title' => 'Sub-area 3.1. Human Resources', 'questions' => range(1, 11)],
            ['title' => 'Sub-area 3.2. Financial Resources', 'questions' => range(12, 17)],
            ['title' => 'Sub-area 3.3. Learning, Physical and IT Facilities', 'questions' => range(18, 27)]
        ]
    ]
];

$selected_area = isset($_GET['area']) && isset($areas[$_GET['area']]) ? $_GET['area'] : 'area1';
$area = $areas[$selected_area];

// Define sub-areas based on the selected area's definition
$sub_areas = isset($area['sub_areas']) ? $area['sub_areas'] : [];

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
            <?php if (!empty($sub_areas)): ?>
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

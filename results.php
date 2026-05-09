<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/db.php';

$areas = [
    'area1' => [
            'name' => 'Area 1: Governance and Management',
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
            ],
            'sub_areas' => [
                ['title' => 'Sub-area 1.1. Vision Mission', 'questions' => range(1, 6)],
                ['title' => 'Sub-area 1.2. Leadership and Management', 'questions' => range(7, 10)],
                ['title' => 'Sub-area 1.3. Strategic Management', 'questions' => range(11, 17)],
                ['title' => 'Sub-area 1.4. Policy Formulation and Implementation', 'questions' => range(18, 23)],
                ['title' => 'Sub-area 1.5. Risk Management', 'questions' => range(24, 28)]
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
            ],
            'sub_areas' => [
                ['title' => 'Sub-area 2.1. Internal Quality Assurance System', 'questions' => range(1, 7)],
                ['title' => 'Sub-area 2.2. External Quality Assurance', 'questions' => range(8, 12)],
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
    ],
    'area4' => [
        'name' => 'Area 4: Teaching-Learning',
        'table' => 'area4_responses',
        'questions' => [
            'The medical program is aligned with the vision, mission, and goals of the institution.',
            'The content includes basic biomedical sciences, research, clinical sciences, skills, and behavioral and social sciences.',
            'An established system is in place for the design, development, and review of the medical program.',
            'Delivery plans and syllabi are developed for each course and communicated based on expected learning outcomes.',
            'The course objectives, including the expected learning outcomes of the medical program, are established.',
            'Students and key stakeholders participate in the design, development, and review of the medical program.',
            'The system of managing the medical program is regularly assessed for improvement and updating.',
            'All students are exposed to various learning opportunities in which priority health care concerns are addressed, high-quality and cost-effective health care is provided, and the practice of health care to the underserved.',
            'Students see patients and interact with teams of health professionals to develop the necessary knowledge, skills, and attitudes for providing competent and compassionate patient care.',
            // Sub-area 4.2
            'There is a system to select, develop, use and evaluate appropriate teaching and learning methods and activities.',
            'The methods and activities employed are aligned with the educational philosophy of the institution.',
            'The teaching-learning strategies are adopted to a virtual or blended mode of instruction.',
            'Whenever possible, interprofessional education and health teams are incorporated as teaching-learning strategies.',
            "Stakeholders' feedback is considered in selecting, developing, and using teaching-learning methods and activities.",
            'The methods and activities adopted to promote the achievement of the learning outcomes and promote life-long learning.',
            'Monitoring and evaluating the methods and activities deployed for improvement using current innovations and trends in teaching-learning modalities are regularly done.',
            'There is a functioning curriculum committee responsible for monitoring and evaluating the teaching-learning methods and activities.',
            // Sub-area 4.3
            'There is an established system to track students\' progress from admission, their progression from one level to the next, up to the time of graduation.',
            'Various assessment methods are aligned with the achievement of the expected learning outcomes of the course and the medical program and are valid, reliable, and fair.',
            'Assessment methods are adopted to a virtual or blended mode of instruction.',
            'A system is in place to ensure the integrity of the assessment process.',
            'Exit interviews of graduating students are regularly conducted to serve as inputs for assessment methods and course improvements.',
            'Methods for assessment and results are regularly reviewed and evaluated for improvement.',
            'There is an appeal process for assessment results.'
        ],
        'sub_areas' => [
            ['title' => 'Sub-area 4.1. Curricular Programs', 'questions' => range(1, 9)],
            ['title' => 'Sub-area 4.2. Teaching and Learning Methods', 'questions' => range(10, 17)],
            ['title' => 'Sub-area 4.3. Assessment Methods', 'questions' => range(18, 24)]
        ]
    ],
    'area5' => [
            'name' => 'Area 5: Student Services',
            'table' => 'area5_responses',
            'questions' => [
                // Sub-area 5.1
                'A system with defined plans, structures, and policies is established for the recruitment and admission of students.',
                'Criteria for student selection and placement are defined, promoting proper matching of student aptitudes and capabilities to the medical program.',
                'Defined procedures are implemented to ensure effective implementation of recruitment, admission, and placement of students.',
                'Measures are undertaken to monitor the effectiveness of the system for recruitment, admission, and placement.',
                'Student recruitment, admission, and placement are improved to ensure that they remain relevant and practical.',
                'Student recruitment and selection processes conform to the regulatory standards set for admission to the medical education program.',
                "The institution's admission policies and student selection processes are widely publicized.",
                // Sub-area 5.2
                'The medical school has a well-defined, comprehensive system to support the academic needs of students.',
                'The medical school has accessible programs for student services to support the academic and non-academic needs of students.',
                'There is a process to identify and monitor students needing personal counseling, academic or financial support.',
                'There is provision for adequate, accessible, and affordable health services to students.',
                'There are adequate financial and physical resources and qualified support staff appointed to provide student services and support.',
                'Measures are undertaken to review the effectiveness of the programs for student services and support and student monitoring systems.',
                'Student services and support and student monitoring systems are improved to meet the needs of students according to established standards.',
                'The available student services are gender-sensitive and culturally appropriate.'
            ],
            'sub_areas' => [
                ['title' => 'Sub-Area 5.1. Student Recruitment, Admission, and Placement', 'questions' => range(1, 7)],
                ['title' => 'Sub-area 5.2. Student Services Programs and Support', 'questions' => range(8, 15)]
            ]
        ],
        'area6' => [
            'name' => 'Area 6. External Relations',
            'table' => 'area6_responses',
            'questions' => [
                'The school has a policy for national and international collaboration with other educational institutions.',
                'The medical school establishes membership in national, regional, or international professional or scientific organizations.',
                'Administrators and faculty members are affiliated with prestigious local, national, regional, and international professional or scientific organizations.',
                'There are consortium arrangements with leading prestigious medical schools in the region.',
                'There are networks and linkages with local or international schools or organizations.',
                'The school has linkages with agencies for funding research.',
                'The school has grants and donations for academic chairs and scholarships from foundations or agencies.',
                'The medical school has interaction with local and national health units and other health sectors.',
                'There are established foreign visiting or exchange professorship arrangements.',
                'There is a good number of exchange or visiting professors.',
                'There are established arrangements for exchange students',
                'The medical school provides time in the curriculum for health promotion and disease prevention in a community.',
                'The curriculum includes contact with patients in relevant clinical settings.',
                'The school and the community share responsibility for the promotion and maintenance of community health.',
                'The medical school promotes leadership in initiating and maintaining development projects in the community.',
                'The medical school provides activities and programs to develop social awareness, concern, and responsibility in the students and faculty.',
                'Medical students plan and implement projects designed to help the community attain self-reliance in health care.',
                'Community projects help raise awareness of social conditions and how they relate to the development of diseases.',
                'Exposure to the community outside the school develops social accountability and responsibility in the students and faculty.',
                'There is a well-planned community-based health program.',
                'The program follows the concepts and principles of primary health care.',
                'The medical school collaborates with the government, the private sector, and the community to support healthcare delivery to the underserved, such as racial and ethnic minorities, displaced persons, the rural and urban poor, and the inhabitants of Geographically Isolated and Disadvantaged Areas (GIDA).'
            ],
            'sub_areas' => [
                ['title' => 'Sub-area 6.1. Networks, Linkages, and Partnerships', 'questions' => range(1, 11)],
                ['title' => 'Sub-area 6.2. Community Engagement and Service', 'questions' => range(12, 22)]
            ]
        ],
        'area7' => [
            'name' => 'Area 7: Research',
            'table' => 'area7_responses',
            'questions' => [
                'There is a defined research agenda with defined goals, plans, policies, and activities.',
                'The research program complies with institutional and regulatory requirements.',
                'An appropriate structure with qualified staff is established.',
                'The human resource has adequate training on technical (good clinical practice, animal care, biosafety) and ethical aspects of research.',
                'Funds and other resources are adequate in the promotion and conduct of research.',
                'The conduct of research is part of the criteria for faculty promotion, awards, and for which they are adequately compensated.',
                'Research linkages, collaboration, and partnerships are established in pursuit of research goals.',
                'The research program and activities are regularly assessed, using performance indicators and stakeholder needs satisfaction, from which the continuous improvement of the research program ensues.',
                'The medical school conducts research that will define and enhance cost effective health care and health care delivery to the underserved.',
                'A system is in place to protect the intellectual property rights of the faculty and the institutional research outputs.',
                'The management of the intellectual property is regularly assessed for improvement.',
                'Policies and guidelines on the ethical conduct of research and publication are established.',
                'An ethics committee is constituted to ensure that policies and guidelines on intellectual property rights and ethics in research are enforced.'
            ],
            'sub_areas' => [
                ['title' => 'Sub-area 7.1. Research Management and Collaboration', 'questions' => range(1, 9)],
                ['title' => 'Sub-area 7.2. Intellectual Property Rights and Ethics in Research', 'questions' => range(10, 13)],
            ]
        ],
        'area8' => [
            'name' => 'Area 8: Results',
            'table' => 'area8_responses',
            'questions' => [
                'The medical program\'s expected institutional and course learning outcomes are defined, monitored, and assessed for improvement.',
                'All courses of the medical program\'s pass and dropout rates are identified, monitored, and assessed for improvement.',
                'The average time to graduate for the program is identified, monitored, and assessed for improvement.',
                'A career progression program is established, monitored, and assessed for improvement.',
                'The performance rate within or above the national passing rate and the failure rates of graduates in the physician licensure examination (PLE) are identified, monitored, and assessed for improvement.',
                'The satisfaction levels of key stakeholders on the quality of graduates are established, monitored, and assessed for improvements.',
                'The nature and volume of community engagement and service activities are identified, monitored, and assessed for improvement.',
                'The societal impact and achievements of these activities are identified, monitored, and assessed for improvement.',
                'The impact on the medical school, faculty, staff, and students is identified, monitored, and assessed for improvement.',
                'The impact on these activities\' beneficiaries and other stakeholders is identified, monitored, and assessed for improvement.',
                'The nature and number of research outputs done by faculty members and staff are documented, monitored, and assessed for improvement.',
                'The nature and number of researches done by research teams and students are documented and assessed for improvement.',
                'The nature and number of research publications are documented, monitored, and assessed for improvement.',
                'The nature and number of intellectual properties are documented, monitored, and assessed for improvement.',
                'The impact of research outputs and their publications are identified, monitored, and assessed for improvement.',
                'The stakeholders\' satisfaction in research activities is determined to guide further research development in the institution.',
                'Asset acquisition and placement, retention, and disposal are monitored and assessed for improvement.',
                'Financing in terms of debt, equity, grants, or endowments is monitored and assessed for improvement.',
                'Education, research, and service activities measured in income and expenditure streams are monitored and assessed for improvement.',
                'Cash flows are established, monitored, and assessed for improvement.',
                'Reserves and savings are established, monitored, and assessed for improvement.',
                'Indicators of a reputation for quality program offerings, research, and extension activities are identified, monitored, and assessed for improvement.',
                'Best practices of the medical school are identified, monitored, and assessed for improvement.'
            ],
            'sub_areas' => [
                ['title' => 'Sub-area 8.1 Educational Results', 'questions' => range(1, 6)],
                ['title' => 'Sub-area 8.2. Community Engagement and Service Results', 'questions' => range(7, 10)],
                ['title' => 'Sub-area 8.3. Research Results', 'questions' => range(11, 16)],
                ['title' => 'Sub-area 8.4. Financial and Competitiveness Results', 'questions' => range(17, 23)],
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

    $where_clauses = [];
    if (isset($_GET['program']) && $_GET['program'] !== '') {
        $where_clauses[] = "program = '" . $conn->real_escape_string($_GET['program']) . "'";
    }
    if (isset($_GET['role']) && $_GET['role'] !== '') {
        $where_clauses[] = "role = '" . $conn->real_escape_string($_GET['role']) . "'";
    }

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $res = $conn->query($sql);
if ($res && $row = $res->fetch_assoc()) {
    $total_responses = $row['cnt'];
    for ($i = 1; $i <= count($area['questions']); $i++) {
        $summary[$i] = [
            'avg' => is_null($row["avg_q$i"]) ? null : round($row["avg_q$i"], 2)
        ];
    }
    $total_avg_rating = 0;
    $count_avg_ratings = 0;
    foreach ($summary as $item) {
        if (isset($item['avg']) && $item['avg'] !== null) {
            $total_avg_rating += $item['avg'];
            $count_avg_ratings++;
        }
    }
    $overall_avg_rating = $count_avg_ratings > 0 ? round($total_avg_rating / $count_avg_ratings, 2) : '-';
}

// Query comments
    $comments_data = [];
    $comments_sql = "SELECT comments FROM {$area['table']}";
    $comments_where_clauses = [];

    if (!empty($where_clauses)) {
        $comments_where_clauses = array_merge($comments_where_clauses, $where_clauses);
    }

    $comments_where_clauses[] = "comments IS NOT NULL";
    $comments_where_clauses[] = "comments != ''";

    if (!empty($comments_where_clauses)) {
        $comments_sql .= " WHERE " . implode(" AND ", $comments_where_clauses);
    }
    $comments_sql .= " ORDER BY submitted_at DESC";

    $comments_res = $conn->query($comments_sql);
    if ($comments_res && $comments_res->num_rows > 0) {
        while ($comment_row = $comments_res->fetch_assoc()) {
            $comments_data[] = $comment_row['comments'];
        }
    }

    // Debugging output
  

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Results - Admin Dashboard</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            --card-bg: rgba(255, 255, 255, 0.95);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --input-border: #cbd5e1;
            --input-focus: #3b82f6;
            --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --success: #10b981;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            padding: 40px 20px;
            color: var(--text-main);
            line-height: 1.6;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 40px;
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.025em;
        }

        .logout-btn {
            padding: 10px 20px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        .filter-section {
            background: #f8fafc;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 40px;
            border: 1px solid var(--border-color);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-group label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .filter-group select {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid var(--input-border);
            background: white;
            font-family: inherit;
            font-size: 1rem;
            color: var(--text-main);
            transition: var(--transition);
            cursor: pointer;
        }

        .filter-group select:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-color);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .results-table-container {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            margin-bottom: 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            font-size: 0.95rem;
        }

        th {
            background: #f1f5f9;
            padding: 16px 20px;
            text-align: left;
            font-weight: 700;
            color: var(--text-main);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .subarea-header td {
            background: #eff6ff;
            color: var(--primary-color);
            font-weight: 800;
            font-size: 1rem;
            padding: 12px 20px;
        }

        .subarea-summary td {
            background: #f8fafc;
            font-weight: 700;
            color: var(--text-main);
        }

        .rating-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 8px;
            font-weight: 700;
            background: #eff6ff;
            color: var(--primary-color);
            min-width: 45px;
            text-align: center;
        }

        .actions-bar {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: var(--text-main);
            border: 1.5px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success);
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            padding: 40px 20px;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            background: white;
            margin: auto;
            max-width: 800px;
            width: 100%;
            border-radius: 24px;
            padding: 40px;
            position: relative;
            box-shadow: var(--shadow);
            max-height: 80vh;
            overflow-y: auto;
        }

        .close-button {
            position: absolute;
            top: 24px;
            right: 24px;
            font-size: 2rem;
            color: var(--text-muted);
            cursor: pointer;
            line-height: 1;
            transition: var(--transition);
        }

        .close-button:hover {
            color: var(--text-main);
        }

        .comment-item {
            background: #f8fafc;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 16px;
            border: 1.5px solid var(--border-color);
            transition: var(--transition);
        }

        .comment-item:hover {
            border-color: var(--input-focus);
            background: white;
        }

        .comment-text {
            color: var(--text-main);
            font-size: 1rem;
            line-height: 1.6;
        }

        @media (max-width: 640px) {
            .container { padding: 24px 20px; border-radius: 0; }
            .header { flex-direction: column; gap: 20px; text-align: center; }
            .stats-grid { grid-template-columns: 1fr; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h2>Survey Results</h2>
            <form action="logout.php" method="post">
                <button type="submit" class="logout-btn">Logout Dashboard</button>
            </form>
        </header>

        <section class="filter-section">
            <form method="get" action="" class="filter-grid">
                <div class="filter-group">
                    <label for="area">Survey Area</label>
                    <select name="area" id="area" onchange="this.form.submit()">
                        <?php foreach ($areas as $key => $a): ?>
                            <option value="<?= htmlspecialchars($key) ?>" <?= $key === $selected_area ? 'selected' : '' ?>><?= htmlspecialchars($a['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="program">Program Filter</label>
                    <select name="program" id="program" onchange="this.form.submit()">
                        <option value="">All Programs</option>
                        <?php
                            $program_query = $conn->query("SELECT DISTINCT program FROM {$area['table']}");
                            if ($program_query) {
                                while ($p_row = $program_query->fetch_assoc()) {
                                    $selected = (isset($_GET['program']) && $_GET['program'] == $p_row['program']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($p_row['program']) . '" ' . $selected . '>' . htmlspecialchars($p_row['program']) . '</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="role">Role Filter</label>
                    <select name="role" id="role" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <?php
                            $role_query = $conn->query("SELECT DISTINCT role FROM {$area['table']}");
                            if ($role_query) {
                                while ($r_row = $role_query->fetch_assoc()) {
                                    $selected = (isset($_GET['role']) && $_GET['role'] == $r_row['role']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($r_row['role']) . '" ' . $selected . '>' . htmlspecialchars($r_row['role']) . '</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </form>
        </section>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?= $total_responses ?></div>
                <div class="stat-label">Total Responses</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?= $overall_avg_rating ?></div>
                <div class="stat-label">Overall Average</div>
            </div>
        </section>

        <div class="results-table-container">
            <table>
                <thead>
                    <tr>
                        <th>Question Description</th>
                        <th style="width: 140px; text-align: center;">Avg Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sub_areas)): ?>
                        <?php foreach ($sub_areas as $sub_idx => $sub): ?>
                                <?php
                                    $sub_area_total_rating = 0;
                                    $sub_area_question_count = 0;
                                ?>
                            <tr class="subarea-header">
                                <td colspan="2">
                                    <?= htmlspecialchars($sub['title']) ?>
                                </td>
                            </tr>
                            <?php foreach ($sub['questions'] as $j => $i): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight: 500;"><?= ($j + 1) ?>. <?= htmlspecialchars($area['questions'][$i-1]) ?></div>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php
                                            $avg_rating = isset($summary[$i]['avg']) && $summary[$i]['avg'] !== null ? $summary[$i]['avg'] : '-';
                                            if ($avg_rating !== '-') {
                                                $sub_area_total_rating += $avg_rating;
                                                $sub_area_question_count++;
                                            }
                                        ?>
                                        <span class="rating-badge"><?= $avg_rating ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr class="subarea-summary">
                                <td style="text-align: right; padding-right: 32px; font-weight: 700;">Sub-area Average</td>
                                <td style="text-align: center;">
                                    <span class="rating-badge" style="background: var(--primary-color); color: white;">
                                        <?= $sub_area_question_count > 0 ? round($sub_area_total_rating / $sub_area_question_count, 2) : '-' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php foreach ($area['questions'] as $i => $q): ?>
                        <tr>
                            <td><?= htmlspecialchars($q) ?></td>
                            <td style="text-align: center;">
                                <span class="rating-badge"><?= isset($summary[$i+1]['avg']) && $summary[$i+1]['avg'] !== null ? $summary[$i+1]['avg'] : '-' ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <footer class="actions-bar">
            <a href="index.php" class="btn btn-secondary">
                <span>← Back to List</span>
            </a>
            <button id="exportExcelBtn" class="btn btn-success">
                <span>Export to Excel</span>
            </button>
            <?php if (!empty($comments_data)): ?>
                <button id="openCommentsModal" class="btn btn-primary">
                    <span>View Comments (<?= count($comments_data) ?>)</span>
                </button>
            <?php endif; ?>
        </footer>
    </div>

    <!-- Comments Modal -->
    <?php if (!empty($comments_data)): ?>
    <div id="commentsModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid var(--border-color);">
                User Comments - <?= htmlspecialchars($area['name']) ?>
            </h3>
            <div class="comments-list">
                <?php foreach ($comments_data as $comment): ?>
                    <div class="comment-item">
                        <p class="comment-text"><?= nl2br(htmlspecialchars($comment)) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        // Modal Logic
        const modal = document.getElementById("commentsModal");
        const btn = document.getElementById("openCommentsModal");
        const span = document.querySelector(".close-button");

        if (btn) {
            btn.onclick = () => modal.style.display = "flex";
        }

        if (span) {
            span.onclick = () => modal.style.display = "none";
        }

        window.onclick = (event) => {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Excel Export
        document.getElementById('exportExcelBtn').addEventListener('click', function() {
            // Include current filters in export
            const urlParams = new URLSearchParams(window.location.search);
            window.location.href = 'export_excel.php?' + urlParams.toString();
        });
    </script>
</body>
</html>
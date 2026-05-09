<?php

require_once __DIR__ . '/config/db.php';

// Function to parse questions from the text file
function parseQuestions($area_name) {
    $file_path = __DIR__ . '/Textfiles/' . $area_name . '_survey.txt';
    $questions = [];
    if (file_exists($file_path)) {
        $content = file_get_contents($file_path);
        // Find the position of "Questions:"
        $questions_start_pos = strpos($content, "Questions:");
        if ($questions_start_pos !== false) {
            // Extract content after "Questions:"
            $questions_content = substr($content, $questions_start_pos);
            preg_match_all('/\d+\.\s(.*?)(?=\n\d+\.\s|\n\n|\Z)/s', $questions_content, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $q) {
                    $questions[] = trim($q);
                }
            }
        }
    }
    return $questions;
}

// Match results.php's area/sub-area structure
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
                'The medical school has a policy for national and international collaboration with other educational institutions.',
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
                'There is a well-planned community-based health program.'
            ],
            'sub_areas' => [
                ['title' => 'Sub-area 6.1. Linkages and Consortia', 'questions' => range(1, 11)],
                ['title' => 'Sub-area 6.2. Community Engagement', 'questions' => range(12, 20)]
            ]
        ],
        'area7' => [
            'name' => 'Area 7. Research',
            'table' => 'area7_responses',
            'questions' => [
                'The medical school has a research agenda aligned with the institutional vision, mission, and goals.',
                'The research agenda is responsive to national and local health needs and priorities.',
                'The research agenda is developed with the participation of internal and external stakeholders.',
                'The medical school has policies and guidelines for the conduct of research.',
                'The medical school has an ethics review committee that evaluates research proposals.',
                'The medical school provides training and development programs for faculty and students on research methods and ethics.',
                'The medical school provides adequate resources for research activities.',
                'The medical school disseminates research findings through publications, conferences, and other platforms.',
                'The medical school utilizes research findings to improve its educational programs and health services.',
                'The medical school promotes a culture of research among its faculty and students.'
            ],
            'sub_areas' => [
                ['title' => 'Sub-area 7.1. Research Agenda', 'questions' => range(1, 3)],
                ['title' => 'Sub-area 7.2. Research Policies and Guidelines', 'questions' => range(4, 5)],
                ['title' => 'Sub-area 7.3. Research Capability Building', 'questions' => range(6, 7)],
                ['title' => 'Sub-area 7.4. Research Dissemination and Utilization', 'questions' => range(8, 10)]
            ]
        ],
        'area8' => [
            'name' => 'Area 8. Extension and Community Service',
            'table' => 'area8_responses',
            'questions' => [
                'The medical school has an extension and community service agenda aligned with the institutional vision, mission, and goals.',
                'The extension and community service agenda is responsive to national and local health needs and priorities.',
                'The extension and community service agenda is developed with the participation of internal and external stakeholders.',
                'The medical school has policies and guidelines for the conduct of extension and community service.',
                'The medical school provides training and development programs for faculty and students on extension and community service methods and ethics.',
                'The medical school provides adequate resources for extension and community service activities.',
                'The medical school disseminates extension and community service outputs and outcomes.',
                'The medical school utilizes extension and community service findings to improve its educational programs and health services.',
                'The medical school promotes a culture of extension and community service among its faculty and students.'
            ],
            'sub_areas' => [
                ['title' => 'Sub-area 8.1. Extension and Community Service Agenda', 'questions' => range(1, 3)],
                ['title' => 'Sub-area 8.2. Extension and Community Service Policies and Guidelines', 'questions' => range(4, 5)],
                ['title' => 'Sub-area 8.3. Extension and Community Service Capability Building', 'questions' => range(6, 7)],
                ['title' => 'Sub-area 8.4. Extension and Community Service Dissemination and Utilization', 'questions' => range(8, 9)]
            ]
        ]
];

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="survey_results.xls"');
header('Cache-Control: max-age=0');

$output = fopen('php://output', 'w');

// Write headers to Excel file
fputcsv($output, ['Area', 'Sub-area', 'Question Number', 'Question', 'Average Rating', 'Comments']);

foreach ($areas as $area_key => $area_data) {
    $area_name = $area_data['name'];
    $table_name = $area_data['table'];
    $questions_list = parseQuestions($area_key);

    // Fetch all responses for the current area
    $sql = "SELECT * FROM $table_name";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $all_responses = [];
        while ($row = $result->fetch_assoc()) {
            $all_responses[] = $row;
        }

        // Write Area Header
        fputcsv($output, [$area_name, '', '', '', '', '']);

        // Calculate averages for each question
        $question_averages = [];
        foreach ($questions_list as $q_num => $question_text) {
            $total_rating = 0;
            $response_count = 0;
            foreach ($all_responses as $response) {
                $col_name = 'q' . ($q_num + 1);
                if (isset($response[$col_name]) && is_numeric($response[$col_name])) {
                    $total_rating += $response[$col_name];
                    $response_count++;
                }
            }
            $question_averages[$q_num + 1] = $response_count > 0 ? round($total_rating / $response_count, 2) : '-';
        }

        // Write data to Excel file
        foreach ($area_data['sub_areas'] as $sub_area) {
            $sub_area_title = $sub_area['title'];
            // Write Sub-area Header
            fputcsv($output, ['', $sub_area_title, '', '', '', '']);
            foreach ($sub_area['questions'] as $q_num) {
                $question_text = $questions_list[$q_num - 1];
                $average_rating = $question_averages[$q_num];
                fputcsv($output, ['', '', $q_num, $question_text, $average_rating, '']);
            }
        }

        // Collect all comments for the area
        $all_comments = [];
        foreach ($all_responses as $response) {
            if (!empty($response['comments'])) {
                $all_comments[] = $response['comments'];
            }
        }

        if (!empty($all_comments)) {
            fputcsv($output, ['','','','','','']); // Blank row for separation
            fputcsv($output, [$area_name, 'All Comments for ' . $area_name, '', '', '', implode("\n", $all_comments)]);
        }
    }
    fputcsv($output, ['','','','','','']); // Blank row for separation between areas
}

fclose($output);
$conn->close();

exit();

?>
<?php
include_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $questions = [];
    for ($i = 1; $i <= 27; $i++) {
        $q_name = 'q' . $i;
        $questions[$q_name] = isset($_POST[$q_name]) ? (int)$_POST[$q_name] : null;
    }

    $columns = implode(', ', array_keys($questions));
    $placeholders = implode(', ', array_fill(0, count($questions), '?'));
    $values = array_values($questions);

    $sql = "INSERT INTO area3_responses ($columns, submitted_at) VALUES ($placeholders, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = str_repeat('i', count($values)); // All questions are tinyint, so 'i' for integer
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            header("Location: ../submission_success.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}

function render_rating($name) {
    $labels = [
        5 => '5',
        4 => '4',
        3 => '3',
        2 => '2',
        1 => '1',
        0 => '0',
    ];
    echo '<div class="rating-bar">';
    foreach ($labels as $val => $label) {
        echo '<input type="radio" id="'.$name.'_'.$val.'" name="'.$name.'" value="'.$val.'" required>';
        echo '<label for="'.$name.'_'.$val.'">'.$label.'</label>';
    }
    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area 3: Resource Management Survey</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f6f8fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 700px;
            margin: 32px auto;
            background: #fff;
            padding: 24px 32px;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        }
        h2 {
            text-align: center;
            margin-bottom: 18px;
            font-weight: 600;
            color: #2d3a4a;
        }
        h3 {
            text-align: left;
            margin: 32px 0 18px 0;
            font-size: 1.15rem;
            color: #3b4a5a;
            font-weight: 500;
        }
        .rating-guide {
            background: #f0f4fa;
            border-left: 4px solid #186098;
            padding: 12px 18px;
            margin-bottom: 28px;
            font-size: 15px;
            color: #3b4a5a;
        }
        form {
            margin: 0;
        }
        .section {
            margin-bottom: 18px;
        }
        .question-card {
            background: #f8fafc;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(80,120,200,0.04);
            padding: 18px 16px 12px 16px;
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .question-card label {
            font-weight: 400;
            color: #2d3a4a;
            font-size: 1rem;
            margin-bottom: 0;
        }
        .rating-bar {
            display: flex;
            gap: 10px;
            margin-top: 2px;
            justify-content: center;
        }
        .rating-bar input[type="radio"] {
            display: none;
        }
        .rating-bar label {
            display: inline-block;
            background: #e3eafc;
            color: #3b4a5a;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            font-weight: 500;
            border: 1.5px solid #c3d2f7;
            min-width: 32px;
            text-align: center;
            box-shadow: 0 1px 2px rgba(80,120,200,0.04);
        }
        .rating-bar input[type="radio"]:checked + label {
            background: #186098;
            color: #fff;
            border-color: #186098;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(80,120,200,0.10);
        }
        .rating-bar label:hover, .rating-bar label:focus {
            background: #124C7A;
            color: #fff;
            outline: none;
        }
        button {
            background: linear-gradient(90deg, #186098 60%, #124C7A 100%);
            color: #fff;
            border: none;
            padding: 14px 0;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            margin: 32px 0 0 0;
            box-shadow: 0 2px 8px rgba(80,120,200,0.08);
            transition: background 0.2s;
        }
        button:hover {
            background: linear-gradient(90deg, #124C7A 60%, #186098 100%);
        }
        @media (max-width: 900px) {
            .container {
                max-width: 98vw;
                padding: 10px 2vw;
            }
        }
        @media (max-width: 600px) {
            .container {
                padding: 6vw 2vw;
                border-radius: 0;
            }
            h2 { font-size: 1.2rem; }
            h3 { font-size: 1rem; }
            .question-card label { font-size: 0.98rem; }
            .rating-bar label { padding: 8px 8px; font-size: 15px; }
        }
        .standard-box {
            background: #eaf3ff;
            border-left: 4px solid #186098;
            padding: 14px 18px 10px 18px;
            margin-bottom: 18px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(80,120,200,0.04);
        }
        .standard-title {
            font-weight: 600;
            color: #186098;
            font-size: 1.05rem;
            margin-bottom: 4px;
        }
        .standard-desc {
            color: #3b4a5a;
            font-size: 0.98rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>AREA 3. RESOURCE MANAGEMENT</h2>
        <div class="rating-guide">
            <strong>Rating Guide:</strong><br>
            5 - Excellent : The practice is exemplary and serves as a model to others. The implementation of the criterion has led to excellent results.<br>
            4 - Very Good : The criterion has been effectively implemented, and this has led to very good results.<br>
            3 - Good : The criterion has been implemented adequately and has led to good results.<br>
            2 - Needs Minor Improvement : The criterion has been implemented but needs minor improvement. In addition, the implementation has led to inconsistent or limited results.<br>
            1 - Needs Major Improvement : The criterion has been inadequately implemented and needs significant improvement. The implementation has led to insignificant or unsatisfactory results.<br>
            0 - Not Implemented : The criterion has not been implemented. Furthermore, no evidence is presented to show that initiatives have been carried out to implement it.
        </div>
        <form method="post" action="">
            <div class="section">
                <h3>Sub-area 3.1. Human Resources</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 8.</div>
                    <div class="standard-desc">The institution has adequate and qualified human resources, both teaching and non-teaching, that enable it to perform its teaching, research, and community service functions. It has programs in place for the recruitment, selection, hiring, deployment, training, and retirement of personnel.</div>
                </div>
                <div class="question-card">
                    <label>1. Human resource plans, policies, and programs are defined and implemented to enable the medical school to achieve its teaching, research, and community service functions.</label>
                    <?php render_rating('q1'); ?>
                </div>
                <div class="question-card">
                    <label>2. Recruitment, selection, and hiring policies are formulated and communicated and are consistently applied.</label>
                    <?php render_rating('q2'); ?>
                </div>
                <div class="question-card">
                    <label>3. Training and development programs are needs-based and are provided to employees.</label>
                    <?php render_rating('q3'); ?>
                </div>
                <div class="question-card">
                    <label>4. Deployment, promotion, succession, and career pathing programs are in place.</label>
                    <?php render_rating('q4'); ?>
                </div>
                <div class="question-card">
                    <label>5. A performance management system covering job evaluation, reward, recognition, coaching, and mentoring is in place.</label>
                    <?php render_rating('q5'); ?>
                </div>
                <div class="question-card">
                    <label>6. Salaries, incentives, and benefits are set at levels that ensure the medical school attracts and retains qualified staff.</label>
                    <?php render_rating('q6'); ?>
                </div>
                <div class="question-card">
                    <label>7. Provisions for resignation, termination, and retirement are in place.</label>
                    <?php render_rating('q7'); ?>
                </div>
                <div class="question-card">
                    <label>8. There is a sufficient workforce to attend to the needs of the medical school.</label>
                    <?php render_rating('q8'); ?>
                </div>
                <div class="question-card">
                    <label>9. The working environment is risk-free and safe for the employees.</label>
                    <?php render_rating('q9'); ?>
                </div>
                <div class="question-card">
                    <label>10. Policies and programs are in place to promote the well-being of employees.</label>
                    <?php render_rating('q10'); ?>
                </div>
                <div class="question-card">
                    <label>11. Human resource plans, policies, and programs are periodically assessed for improvement.</label>
                    <?php render_rating('q11'); ?>
                </div>
            </div>

            <div class="section">
                <h3>Sub-area 3.2. Financial Resources</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 9.</div>
                    <div class="standard-desc">A system is in place to manage the institution's financial resources, including efficient sourcing, allocation, use, safeguarding, and accounting.</div>
                </div>
                <div class="question-card">
                    <label>1. The financial management system is designed to make resources available to support the medical school's vision, mission, and goals, particularly in teaching, research, and community service.</label>
                    <?php render_rating('q12'); ?>
                </div>
                <div class="question-card">
                    <label>2. There are adequate funds to guarantee the viability of medical school operations and programs, with provisions for good sourcing of finances when needed.</label>
                    <?php render_rating('q13'); ?>
                </div>
                <div class="question-card">
                    <label>3. A participative budgeting process is in place, which includes regular budget performance reports and analysis.</label>
                    <?php render_rating('q14'); ?>
                </div>
                <div class="question-card">
                    <label>4. Accounting internal controls function effectively to safeguard the assets, promote the integrity of the accounting records, and ensure compliance with regulatory requirements.</label>
                    <?php render_rating('q15'); ?>
                </div>
                <div class="question-card">
                    <label>5. Internal and external audits are regularly carried out to ensure the reliability of accounting systems and reports.</label>
                    <?php render_rating('q16'); ?>
                </div>
                <div class="question-card">
                    <label>6. Responsibilities for asset custody, use, control, and accountability are clearly defined.</label>
                    <?php render_rating('q17'); ?>
                </div>
            </div>

            <div class="section">
                <h3>Sub-area 3.3. Learning, Physical and IT Facilities</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 10.</div>
                    <div class="standard-desc">The institution has adequate, conducive, up-to-date, well-maintained, and safe facilities to support the functions of teaching-learning, research, and community service.</div>
                </div>
                <div class="question-card">
                    <label>1. There is a facilities development plan with a sufficient budget that is documented and regularly updated.</label>
                    <?php render_rating('q18'); ?>
                </div>
                <div class="question-card">
                    <label>2. The plan reflects consideration for environmental responsibility in its programs.</label>
                    <?php render_rating('q19'); ?>
                </div>
                <div class="question-card">
                    <label>3. There is an office responsible for the development, maintenance, and safety of the physical facilities.</label>
                    <?php render_rating('q20'); ?>
                </div>
                <div class="question-card">
                    <label>4. Infrastructures are adequate and relevant to support teaching-learning, research, and community service.</label>
                    <?php render_rating('q21'); ?>
                </div>
                <div class="question-card">
                    <label>5. Classrooms, lecture halls, seminar rooms, and computer rooms are adequate, kept clean, free from distractions, and conducive for learning.</label>
                    <?php render_rating('q22'); ?>
                </div>
                <div class="question-card">
                    <label>6. Library and laboratories are adequate, accessible, up-to-date, and with a budget for developing collections.</label>
                    <?php render_rating('q23'); ?>
                </div>
                <div class="question-card">
                    <label>7. The IT facilities and infrastructure, both hardware and software, are adequate, up-to-date, and secure.</label>
                    <?php render_rating('q24'); ?>
                </div>
                <div class="question-card">
                    <label>8. There are sufficient provisions in the use of physical facilities to promote the health and safety of students and staff.</label>
                    <?php render_rating('q25'); ?>
                </div>
                <div class="question-card">
                    <label>9. The medical school is compliant with contractual and government requirements as to physical and IT facilities.</label>
                    <?php render_rating('q26'); ?>
                </div>
                <div class="question-card">
                    <label>10. Some facilities and provisions cater to people with special needs.</label>
                    <?php render_rating('q27'); ?>
                </div>
            </div>
            <button type="submit">Submit Survey</button>
        </form>
    </div>
</body>
</html>

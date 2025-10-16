<?php
session_start();
include_once '../config/db.php';

$program = isset($_GET['program']) ? htmlspecialchars($_GET['program']) : '';
$role = isset($_GET['role']) ? htmlspecialchars($_GET['role']) : '';

// Redirect if program or role are not set
if (empty($program) || empty($role)) {
    header("Location: ../selection.php");
    exit();
}

// Check if this survey has already been submitted in the current session
$session_key = 'survey_submitted_area1_' . md5($program . $role);
if (isset($_SESSION[$session_key]) && $_SESSION[$session_key] === true) {
    header("Location: ../submission_success.php?program=" . urlencode($program) . "&role=" . urlencode($role));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $questions = [];
    for ($i = 1; $i <= 28; $i++) {
        $q_name = 'q' . $i;
        $questions[$q_name] = isset($_POST[$q_name]) ? (int)$_POST[$q_name] : null;
    }

    $columns = implode(', ', array_keys($questions));
    $placeholders = implode(', ', array_fill(0, count($questions), '?'));
    $values = array_values($questions);

    // Add program and role to columns, placeholders, and values
    $columns .= ', program, role';
    $placeholders .= ', ?, ?';
    $values[] = $program;
    $values[] = $role;

    $sql = "INSERT INTO area1_responses ($columns, submitted_at) VALUES ($placeholders, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = str_repeat('i', count($questions)) . 'ss'; // 'i' for integer questions, 's' for program and role (strings)
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            // Set session flag to prevent re-submission
            $_SESSION[$session_key] = true;
            // Redirect to a thank you page or results page
            header("Location: ../submission_success.php?program=" . urlencode($program) . "&role=" . urlencode($role));
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
    <title>Area 1 Survey - Leadership and Governance</title>
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
        @media (max-width: 1024px) {
            .container {
                max-width: 90%;
                margin: 20px auto;
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .container {
                max-width: 95%;
                margin: 15px auto;
                padding: 15px;
            }
            h2 {
                font-size: 1.8rem;
            }
            h3 {
                font-size: 1.1rem;
            }
            .rating-guide, .standard-desc, .question-card label {
                font-size: 0.95rem;
            }
            .rating-bar label {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                max-width: 100%;
                margin: 0;
                padding: 10px;
                border-radius: 0;
                box-shadow: none;
            }
            h2 {
                font-size: 1.5rem;
                margin-bottom: 15px;
            }
            h3 {
                font-size: 1rem;
                margin: 25px 0 15px 0;
            }
            .rating-guide, .standard-desc, .question-card label {
                font-size: 0.9rem;
            }
            .question-card {
                padding: 15px;
            }
            .rating-bar {
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
            }
            .rating-bar label {
                padding: 5px 10px;
                font-size: 0.85rem;
                min-width: 28px;
            }
            button {
                padding: 12px 0;
                font-size: 1rem;
                margin-top: 25px;
            }
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
        <h2>AREA 1. LEADERSHIP AND GOVERNANCE</h2>
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
                <h3>Sub-area 1.1. Vision Mission</h3>
                <div class="standard-box">
                    <div class="standard-title">Standard 1.</div>
                    <div class="standard-desc">The institution has clearly articulated and disseminated the vision and mission statements that reflect its educational philosophy, core values, and goals to key stakeholders.</div>
                </div>
                <div class="question-card">
                    <label>1. The process of articulating the vision and mission statements was carried out involving the participation of the medical school's stakeholders.</label>
                    <?php render_rating('q1'); ?>
                </div>
                <div class="question-card">
                    <label>2. The vision and mission statements are communicated periodically to its stakeholders.</label>
                    <?php render_rating('q2'); ?>
                </div>
                <div class="question-card">
                    <label>3. All sectors of the medical school can identify with and own the vision-mission statements of the institution.</label>
                    <?php render_rating('q3'); ?>
                </div>
                <div class="question-card">
                    <label>4. The medical school defines clear indicators of how the vision and mission are achieved.</label>
                    <?php render_rating('q4'); ?>
                </div>
                <div class="question-card">
                    <label>5. There is a periodic revisiting of the medical school's vision and mission.</label>
                    <?php render_rating('q5'); ?>
                </div>
                <div class="question-card">
                    <label>6. The medical school has a clearly articulated mission statement that addresses the priority health care needs of the community, region, and nation.</label>
                    <?php render_rating('q6'); ?>
                </div>
            </div>
            <div class="section">
                <h3>Sub-area 1.2. Leadership and Management</h3>
                <div class="standard-box">
                    <div class="standard-title">Standard 2.</div>
                    <div class="standard-desc">The institution practices responsible management and models leadership that results in effective and efficient operations.</div>
                </div>
                <div class="question-card">
                    <label>1. The Governing Board and the administrators are well qualified and have the experience to function in their respective roles.</label>
                    <?php render_rating('q7'); ?>
                </div>
                <div class="question-card">
                    <label>2. Management promotes good governance, promoting integrity and accountability.</label>
                    <?php render_rating('q8'); ?>
                </div>
                <div class="question-card">
                    <label>3. Leadership is open to suggestions and proactively anticipating and responding to changes that may affect the medical school's operations.</label>
                    <?php render_rating('q9'); ?>
                </div>
                <div class="question-card">
                    <label>4. Leadership training and succession planning are provided for.</label>
                    <?php render_rating('q10'); ?>
                </div>
            </div>
            <div class="section">
                <h3>Sub-area 1.3. Strategic Management</h3>
                <div class="standard-box">
                    <div class="standard-title">Standard 3.</div>
                    <div class="standard-desc">A strategic planning activity participated in by key stakeholders is periodically conducted to formulate, implement and evaluate plans and programs toward achieving the institution's vision, mission, and goals.</div>
                </div>
                <div class="question-card">
                    <label>1. The medical school periodically undertakes a strategic planning process with the involvement of key stakeholders.</label>
                    <?php render_rating('q11'); ?>
                </div>
                <div class="question-card">
                    <label>2. The plans, programs, and activities are aligned with the medical school's vision, mission, and objectives.</label>
                    <?php render_rating('q12'); ?>
                </div>
                <div class="question-card">
                    <label>3. Relevant external and internal factor conditions are identified and used in the formulation of the plan.</label>
                    <?php render_rating('q13'); ?>
                </div>
                <div class="question-card">
                    <label>4. Plans, programs, and activities have clear and measurable targets and are time bound.</label>
                    <?php render_rating('q14'); ?>
                </div>
                <div class="question-card">
                    <label>5. A system for periodic follow-through and evaluation is in place for plans, programs, and activities.</label>
                    <?php render_rating('q15'); ?>
                </div>
                <div class="question-card">
                    <label>6. Ethics, social responsibility, technology, innovation, and internationalization are considered in formulating the strategic plan.</label>
                    <?php render_rating('q16'); ?>
                </div>
                <div class="question-card">
                    <label>7. Adequate resources are committed to the planning exercise and the implementation and evaluation of the strategic plan.</label>
                    <?php render_rating('q17'); ?>
                </div>
            </div>
            <div class="section">
                <h3>Sub-area 1.4. Policy Formulation and Implementation</h3>
                <div class="standard-box">
                    <div class="standard-title">Standard 4.</div>
                    <div class="standard-desc">The institution has a system for formulating and implementing policies that reflect institutional values, promote its unique culture, make operations efficient, and conform to government regulations and standards.</div>
                </div>
                <div class="question-card">
                    <label>1. A system following the Plan-Do-Check-Act (PDCA) cycle is followed in policy formulation and implementation.</label>
                    <?php render_rating('q18'); ?>
                </div>
                <div class="question-card">
                    <label>2. Policies and procedures promote the medical school's values and the unique culture of the institution.</label>
                    <?php render_rating('q19'); ?>
                </div>
                <div class="question-card">
                    <label>3. They are customer-focused and enforced with transparency, consistency, and fairness.</label>
                    <?php render_rating('q20'); ?>
                </div>
                <div class="question-card">
                    <label>4. They consider interrelationships among the various sectors of the medical school and promote synergy in operations.</label>
                    <?php render_rating('q21'); ?>
                </div>
                <div class="question-card">
                    <label>5. Policies for teaching-learning, research, community engagement, and services are articulated and documented.</label>
                    <?php render_rating('q22'); ?>
                </div>
                <div class="question-card">
                    <label>6. They comply with government regulations and standards.</label>
                    <?php render_rating('q23'); ?>
                </div>
            </div>
            <div class="section">
                <h3>Sub-area 1.5. Risk Management</h3>
                <div class="standard-box">
                    <div class="standard-title">Standard 5.</div>
                    <div class="standard-desc">A risk management system is in place to ensure that the institution is aware of and manages present and future risks.</div>
                </div>
                <div class="question-card">
                    <label>1. A risk management program is in place to assess, communicate and implement initiatives to identify and mitigate current and potential sources of risk.</label>
                    <?php render_rating('q24'); ?>
                </div>
                <div class="question-card">
                    <label>2. Explicit risk management policies and established protocols are defined to forestall any identified risks.</label>
                    <?php render_rating('q25'); ?>
                </div>
                <div class="question-card">
                    <label>3. Management assumes the primary responsibility for managing risks and involves the participation of key stakeholders in initiatives involving risk determination and control.</label>
                    <?php render_rating('q26'); ?>
                </div>
                <div class="question-card">
                    <label>4. Medical school resources are utilized effectively, safeguarded, and sufficiently ensured.</label>
                    <?php render_rating('q27'); ?>
                </div>
                <div class="question-card">
                    <label>5. Transparent monitoring processes are established so that all risk-mitigating efforts are working and are effective.</label>
                    <?php render_rating('q28'); ?>
                </div>
            </div>
            <button type="submit">Submit Survey</button>
        </form>
    </div>
</body>
</html>

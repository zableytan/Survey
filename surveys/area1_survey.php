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

    // Add program, role, and comments to columns, placeholders, and values
    $columns .= ', program, role, comments';
    $placeholders .= ', ?, ?, ?';
    $values[] = $program;
    $values[] = $role;
    $values[] = $_POST['comments'];

    $sql = "INSERT INTO area1_responses ($columns, submitted_at) VALUES ($placeholders, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = str_repeat('i', count($questions)) . 'sss'; // 'i' for integer questions, 's' for program, role, and comments (strings)
        $stmt->bind_param($types, ...$values);

        if ($stmt->execute()) {
            // Set session flag to prevent re-submission
            $_SESSION[$session_key] = true;
            // Redirect to a thank you page or results page
            header("Location: ../submission_success.php?program=" . urlencode($program) . "&role=" . urlencode($role) . "&area=area1");
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
<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area 1 Survey - Leadership and Governance</title>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            max-width: 800px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 48px;
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

        h2 {
            font-size: 1.5rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 32px;
            color: var(--text-main);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .rating-guide {
            background: #f1f5f9;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 40px;
            border: 1px solid var(--border-color);
        }

        .rating-guide strong {
            display: block;
            font-size: 1rem;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .rating-guide p {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        h3 {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 48px 0 24px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--border-color);
            color: var(--text-main);
        }

        .standard-box {
            background: #eff6ff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #dbeafe;
        }

        .standard-title {
            font-weight: 800;
            font-size: 0.85rem;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 4px;
        }

        .standard-desc {
            font-size: 1rem;
            font-weight: 500;
            color: #1e40af;
        }

        .question-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 16px;
            transition: var(--transition);
        }

        .question-card:hover {
            border-color: var(--input-focus);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .question-card label {
            display: block;
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 20px;
            color: var(--text-main);
        }

        .rating-bar {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            max-width: 400px;
            margin: 0 auto;
        }

        .rating-bar input[type="radio"] {
            display: none;
        }

        .rating-bar label {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            background: #fff;
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-muted);
            transition: var(--transition);
            margin-bottom: 0;
        }

        .rating-bar label:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: #eff6ff;
        }

        .rating-bar input[type="radio"]:checked + label {
            background: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
            transform: translateY(-2px);
        }

        .comments-section {
            margin-top: 48px;
        }

        textarea {
            width: 100%;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid var(--input-border);
            background: #fff;
            font-family: inherit;
            font-size: 1rem;
            color: var(--text-main);
            min-height: 120px;
            resize: vertical;
            transition: var(--transition);
        }

        textarea:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        button {
            width: 100%;
            padding: 18px;
            margin-top: 40px;
            background: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 16px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        @media (max-width: 640px) {
            .container {
                padding: 32px 20px;
                border-radius: 0;
            }
            body { padding: 0; background: #fff; }
            .rating-bar {
                gap: 4px;
            }
            .rating-bar label {
                height: 40px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>AREA 1. LEADERSHIP AND GOVERNANCE</h2>
        
        <div class="rating-guide">
            <strong>Rating Guide:</strong>
            <p><strong>5 - Excellent</strong>: Exemplary practice, model to others, excellent results.</p>
            <p><strong>4 - Very Good</strong>: Effective implementation, very good results.</p>
            <p><strong>3 - Good</strong>: Adequate implementation, good results.</p>
            <p><strong>2 - Needs Minor Improvement</strong>: Implemented but needs minor improvement; inconsistent results.</p>
            <p><strong>1 - Needs Major Improvement</strong>: Inadequate implementation; unsatisfactory results.</p>
            <p><strong>0 - Not Implemented</strong>: No implementation or evidence presented.</p>
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

            <div class="comments-section">
                <h3>Additional Comments</h3>
                <textarea name="comments" id="comments" placeholder="Share any additional feedback or observations here..."></textarea>
            </div>

            <button type="submit">Submit Area 1 Survey</button>
        </form>
    </div>
</body>
</html>

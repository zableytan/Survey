<?php
session_start();
include_once '../config/db.php';

$program = isset($_GET['program']) ? htmlspecialchars($_GET['program']) : 'Unknown';
$role = isset($_GET['role']) ? htmlspecialchars($_GET['role']) : 'Unknown';

// Redirect if program or role are not set, or if survey already submitted
if ($program === 'Unknown' || $role === 'Unknown') {
    header("Location: ../selection.php");
    exit();
}

$session_flag_name = 'survey_submitted_area8_' . md5($program . $role);
if (isset($_SESSION[$session_flag_name]) && $_SESSION[$session_flag_name] === true) {
    header("Location: ../submission_success.php?program=" . urlencode($program) . "&role=" . urlencode($role));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set session flag upon successful submission
    $_SESSION[$session_flag_name] = true;

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

    $sql = "INSERT INTO area8_responses ($columns, submitted_at) VALUES ($placeholders, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = str_repeat('i', count($questions)) . 'ss'; // 'i' for integer questions, 's' for program and role (strings)
        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) {
            header("Location: ../submission_success.php?program=" . urlencode($program) . "&role=" . urlencode($role) . "&area=area8");
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
    $labels = [5 => '5', 4 => '4', 3 => '3', 2 => '2', 1 => '1', 0 => '0'];
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
    <title>Area 8 Survey - Results</title>
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
        <h2>AREA 8. RESULTS</h2>
        
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
                <h3>Sub-area 8.1 Educational Results</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 20.</div>
                    <div class="standard-desc">The educational process results include the achievement of the expected learning outcomes, pass rates, dropout rates, the average time to graduate, employability of graduates, pass rates of graduates in board examinations of board-related program offerings, and the satisfaction levels of graduates, among others.</div>
                </div>
                <div class="question-card"><label>1. The medical program's expected institutional and course learning outcomes are defined, monitored, and assessed for improvement.</label> <?php render_rating('q1'); ?></div>
                <div class="question-card"><label>2. All courses of the medical program's pass and dropout rates are identified, monitored, and assessed for improvement.</label> <?php render_rating('q2'); ?></div>
                <div class="question-card"><label>3. The average time to graduate for the program is identified, monitored, and assessed for improvement.</label> <?php render_rating('q3'); ?></div>
                <div class="question-card"><label>4. A career progression program is established, monitored, and assessed for improvement.</label> <?php render_rating('q4'); ?></div>
                <div class="question-card"><label>5. The performance rate within or above the national passing rate and the failure rates of graduates in the physician licensure examination (PLE) are identified, monitored, and assessed for improvement.</label> <?php render_rating('q5'); ?></div>
                <div class="question-card"><label>6. The satisfaction levels of key stakeholders on the quality of graduates are established, monitored, and assessed for improvements.</label> <?php render_rating('q6'); ?></div>
            </div>
            <div class="section">
                <h3>Sub-area 8.2. Community Engagement and Service Results</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 21.</div>
                    <div class="standard-desc">The institution's community engagement and service programs produce results that impact the institution, its stakeholders, and society.</div>
                </div>
                <div class="question-card"><label>1. The nature and volume of community engagement and service activities are identified, monitored, and assessed for improvement.</label> <?php render_rating('q7'); ?></div>
                <div class="question-card"><label>2. The societal impact and achievements of these activities are identified, monitored, and assessed for improvement.</label> <?php render_rating('q8'); ?></div>
                <div class="question-card"><label>3. The impact on the medical school, faculty, staff, and students is identified, monitored, and assessed for improvement.</label> <?php render_rating('q9'); ?></div>
                <div class="question-card"><label>4. The impact on these activities' beneficiaries and other stakeholders is identified, monitored, and assessed for improvement.</label> <?php render_rating('q10'); ?></div>
            </div>
            <div class="section">
                <h3>Sub-area 8.3. Research Results</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 22.</div>
                    <div class="standard-desc">The institution has produced research outputs through new knowledge embodied in publications, citations, journals, research-informed teaching, technology transfers, innovations, inventions, creative works, etc.</div>
                </div>
                <div class="question-card"><label>1. The nature and number of research outputs done by faculty members and staff are documented, monitored, and assessed for improvement.</label> <?php render_rating('q11'); ?></div>
                <div class="question-card"><label>2. The nature and number of researches done by research teams and students are documented and assessed for improvement.</label> <?php render_rating('q12'); ?></div>
                <div class="question-card"><label>3. The nature and number of research publications are documented, monitored, and assessed for improvement.</label> <?php render_rating('q13'); ?></div>
                <div class="question-card"><label>4. The nature and number of intellectual properties are documented, monitored, and assessed for improvement.</label> <?php render_rating('q14'); ?></div>
                <div class="question-card"><label>5. The impact of research outputs and their publications are identified, monitored, and assessed for improvement.</label> <?php render_rating('q15'); ?></div>
                <div class="question-card"><label>6. The stakeholders' satisfaction in research activities is determined to guide further research development in the institution.</label> <?php render_rating('q16'); ?></div>
            </div>
            <div class="section">
                <h3>Sub-area 8.4. Financial and Competitiveness Results</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 23.</div>
                    <div class="standard-desc">The institution's financial performance and competitiveness are measured, monitored, and assessed for improvement and sustainability.</div>
                </div>
                <div class="question-card"><label>1. Asset acquisition and placement, retention, and disposal are monitored and assessed for improvement.</label> <?php render_rating('q17'); ?></div>
                <div class="question-card"><label>2. Financing in terms of debt, equity, grants, or endowments is monitored and assessed for improvement.</label> <?php render_rating('q18'); ?></div>
                <div class="question-card"><label>3. Education, research, and service activities measured in income and expenditure streams are monitored and assessed for improvement.</label> <?php render_rating('q19'); ?></div>
                <div class="question-card"><label>4. Cash flows are established, monitored, and assessed for improvement.</label> <?php render_rating('q20'); ?></div>
                <div class="question-card"><label>5. Reserves and savings are established, monitored, and assessed for improvement.</label> <?php render_rating('q21'); ?></div>
                <div class="question-card"><label>6. Indicators of a reputation for quality program offerings, research, and extension activities are identified, monitored, and assessed for improvement.</label> <?php render_rating('q22'); ?></div>
                <div class="question-card"><label>7. Best practices of the medical school are identified, monitored, and assessed for improvement.</label> <?php render_rating('q23'); ?></div>
            </div>

            <div class="comments-section">
                <h3>Additional Comments</h3>
                <textarea name="comments" id="comments" placeholder="Share any additional feedback or observations here..."></textarea>
            </div>

            <button type="submit">Submit Area 8 Survey</button>
        </form>
    </div>
</body>
</html>

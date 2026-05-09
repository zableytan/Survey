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

$session_flag_name = 'survey_submitted_area6_' . md5($program . $role);
if (isset($_SESSION[$session_flag_name]) && $_SESSION[$session_flag_name] === true) {
    header("Location: ../submission_success.php?program=" . urlencode($program) . "&role=" . urlencode($role));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set session flag upon successful submission
    $_SESSION[$session_flag_name] = true;

    $questions = [];
    for ($i = 1; $i <= 22; $i++) {
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

    $sql = "INSERT INTO area6_responses ($columns, submitted_at) VALUES ($placeholders, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $types = str_repeat('i', count($questions)) . 'ss'; // 'i' for integer questions, 's' for program and role (strings)
        $stmt->bind_param($types, ...$values);
        if ($stmt->execute()) {
            header("Location: ../submission_success.php?program=" . urlencode($program) . "&role=" . urlencode($role) . "&area=area6");
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
    <title>Area 6 Survey - External Relations</title>
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
        <h2>AREA 6. EXTERNAL RELATIONS</h2>
        
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
                <h3>Sub-area 6.1. Networks, Linkages, and Partnerships</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 16.</div>
                    <div class="standard-desc">The institution establishes networks, linkages, and partnerships with local, national, regional, and international agencies and groups to pursue its vision, mission, and goals.</div>
                </div>
                <div class="question-card"><label>1. The school has a policy for national and international collaboration with other educational institutions.</label> <?php render_rating('q1'); ?></div>
                <div class="question-card"><label>2. The medical school establishes membership in national, regional, or international professional or scientific organizations.</label> <?php render_rating('q2'); ?></div>
                <div class="question-card"><label>3. Administrators and faculty members are affiliated with prestigious local, national, regional, and international professional or scientific organizations.</label> <?php render_rating('q3'); ?></div>
                <div class="question-card"><label>4. There are consortium arrangements with leading prestigious medical schools in the region.</label> <?php render_rating('q4'); ?></div>
                <div class="question-card"><label>5. There are networks and linkages with local or international schools or organizations.</label> <?php render_rating('q5'); ?></div>
                <div class="question-card"><label>6. The school has linkages with agencies for funding research.</label> <?php render_rating('q6'); ?></div>
                <div class="question-card"><label>7. The school has grants and donations for academic chairs and scholarships from foundations or agencies.</label> <?php render_rating('q7'); ?></div>
                <div class="question-card"><label>8. The medical school has interaction with local and national health units and other health sectors.</label> <?php render_rating('q8'); ?></div>
                <div class="question-card"><label>9. There are established foreign visiting or exchange professorship arrangements.</label> <?php render_rating('q9'); ?></div>
                <div class="question-card"><label>10. There is a good number of exchange or visiting professors.</label> <?php render_rating('q10'); ?></div>
                <div class="question-card"><label>11. There are established arrangements for exchange students</label> <?php render_rating('q11'); ?></div>
            </div>
            <div class="section">
                <h3>Sub-area 6.2. Community Engagement and Service</h3>
                <div class="standard-box">
                    <div class="standard-title">STANDARD 17.</div>
                    <div class="standard-desc">The institution commits to conduct community engagements and service activities as part of its social responsibility and corporate citizenship.</div>
                </div>
                <div class="question-card"><label>1. The medical school provides time in the curriculum for health promotion and disease prevention in a community.</label> <?php render_rating('q12'); ?></div>
                <div class="question-card"><label>2. The curriculum includes contact with patients in relevant clinical settings.</label> <?php render_rating('q13'); ?></div>
                <div class="question-card"><label>3. The school and the community share responsibility for the promotion and maintenance of community health.</label> <?php render_rating('q14'); ?></div>
                <div class="question-card"><label>4. The medical school promotes leadership in initiating and maintaining development projects in the community.</label> <?php render_rating('q15'); ?></div>
                <div class="question-card"><label>5. The medical school provides activities and programs to develop social awareness, concern, and responsibility in the students and faculty.</label> <?php render_rating('q16'); ?></div>
                <div class="question-card"><label>6. Medical students plan and implement projects designed to help the community attain self-reliance in health care.</label> <?php render_rating('q17'); ?></div>
                <div class="question-card"><label>7. Community projects help raise awareness of social conditions and how they relate to the development of diseases.</label> <?php render_rating('q18'); ?></div>
                <div class="question-card"><label>8. Exposure to the community outside the school develops social accountability and responsibility in the students and faculty.</label> <?php render_rating('q19'); ?></div>
                <div class="question-card"><label>9. There is a well-planned community-based health program.</label> <?php render_rating('q20'); ?></div>
                <div class="question-card"><label>10. The program follows the concepts and principles of primary health care.</label> <?php render_rating('q21'); ?></div>
                <div class="question-card"><label>11. The medical school collaborates with the government, the private sector, and the community to support healthcare delivery to the underserved, such as racial and ethnic minorities, displaced persons, the rural and urban poor, and the inhabitants of Geographically Isolated and Disadvantaged Areas (GIDA).</label> <?php render_rating('q22'); ?></div>
            </div>

            <div class="comments-section">
                <h3>Additional Comments</h3>
                <textarea name="comments" id="comments" placeholder="Share any additional feedback or observations here..."></textarea>
            </div>

            <button type="submit">Submit Area 6 Survey</button>
        </form>
    </div>
</body>
</html>

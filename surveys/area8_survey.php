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
    <title>Area 8: Results Survey</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f6f8fa; margin: 0; padding: 0; }
        .container { max-width: 700px; margin: 32px auto; background: #fff; padding: 24px 32px; border-radius: 14px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
        h2 { text-align: center; margin-bottom: 18px; font-weight: 600; color: #2d3a4a; }
        h3 { text-align: left; margin: 32px 0 18px 0; font-size: 1.15rem; color: #3b4a5a; font-weight: 500; }
        .rating-guide { background: #f0f4fa; border-left: 4px solid #186098; padding: 12px 18px; margin-bottom: 28px; font-size: 15px; color: #3b4a5a; }
        form { margin: 0; }
        .section { margin-bottom: 18px; }
        .question-card { background: #f8fafc; border-radius: 10px; box-shadow: 0 1px 4px rgba(80,120,200,0.04); padding: 18px 16px 12px 16px; margin-bottom: 18px; display: flex; flex-direction: column; gap: 10px; }
        .question-card label { font-weight: 400; color: #2d3a4a; font-size: 1rem; margin-bottom: 0; }
        .rating-bar { display: flex; gap: 10px; margin-top: 2px; justify-content: center; }
        .rating-bar input[type="radio"] { display: none; }
        .rating-bar label { display: inline-block; background: #e3eafc; color: #3b4a5a; padding: 8px 16px; border-radius: 6px; cursor: pointer; transition: background 0.2s, color 0.2s, box-shadow 0.2s; font-weight: 500; border: 1.5px solid #c3d2f7; min-width: 32px; text-align: center; box-shadow: 0 1px 2px rgba(80,120,200,0.04); }
        .rating-bar input[type="radio"]:checked + label { background: #186098; color: #fff; border-color: #186098; font-weight: 600; box-shadow: 0 2px 8px rgba(80,120,200,0.10); }
        .rating-bar label:hover, .rating-bar label:focus { background: #124C7A; color: #fff; outline: none; }
        button { background: linear-gradient(90deg, #186098 60%, #124C7A 100%); color: #fff; border: none; padding: 14px 0; border-radius: 8px; font-size: 1.1rem; font-weight: 600; cursor: pointer; width: 100%; margin: 32px 0 0 0; box-shadow: 0 2px 8px rgba(80,120,200,0.08); transition: background 0.2s; }
        button:hover { background: linear-gradient(90deg, #124C7A 60%, #186098 100%); }
        @media (max-width: 1024px) {
            .container { max-width: 95%; margin: 20px auto; padding: 20px; }
            h2 { font-size: 1.8rem; }
            h3 { font-size: 1.1rem; }
            .rating-guide { font-size: 14px; padding: 10px 15px; }
            .question-card label { font-size: 0.95rem; }
            .rating-bar label { padding: 7px 14px; font-size: 14px; }
            button { padding: 12px 0; font-size: 1rem; }
        }
        @media (max-width: 768px) {
            .container { max-width: 98%; margin: 15px auto; padding: 15px; }
            h2 { font-size: 1.6rem; }
            h3 { font-size: 1rem; }
            .rating-guide { font-size: 13px; padding: 8px 12px; }
            .question-card { padding: 15px; }
            .question-card label { font-size: 0.9rem; }
            .rating-bar { flex-wrap: wrap; justify-content: center; }
            .rating-bar label { padding: 6px 12px; font-size: 13px; min-width: 30px; }
            button { padding: 10px 0; font-size: 0.95rem; }
        }
        @media (max-width: 480px) {
            .container { padding: 10px; border-radius: 0; margin: 0; max-width: 100%; }
            h2 { font-size: 1.4rem; margin-bottom: 15px; }
            h3 { font-size: 0.95rem; margin: 25px 0 15px 0; }
            .rating-guide { font-size: 12px; padding: 8px; margin-bottom: 20px; }
            .question-card { padding: 10px; margin-bottom: 15px; }
            .question-card label { font-size: 0.85rem; }
            .rating-bar { gap: 5px; }
            .rating-bar label { padding: 5px 8px; font-size: 12px; min-width: 25px; border-radius: 5px; }
            button { padding: 8px 0; font-size: 0.9rem; margin-top: 25px; border-radius: 6px; }
            .standard-box { padding: 10px 12px; }
            .standard-title { font-size: 1rem; }
            .standard-desc { font-size: 0.9rem; }
        }
        .standard-box { background: #eaf3ff; border-left: 4px solid #186098; padding: 14px 18px 10px 18px; margin-bottom: 18px; border-radius: 8px; box-shadow: 0 1px 4px rgba(80,120,200,0.04); }
        .standard-title { font-weight: 600; color: #186098; font-size: 1.05rem; margin-bottom: 4px; }
        .standard-desc { color: #3b4a5a; font-size: 0.98rem; }
    </style>
</head>
<body>
<div class="container">
    <h2>AREA 8. RESULTS</h2>
    <div class="rating-guide">
        <strong>Rating Guide:</strong><br>
        5 - Excellent : The practice is exemplary and serves as a model to others. The implementation of the criterion has led to excellent results.<br>
        4 - Very Good : The criterion has been effectively implemented, and this has led to very good results.<br>
        3 - Good : The criterion has been implemented adequately and has led to good results.<br>
        2 - Needs Minor Improvement : The criterion has been implemented but needs minor improvement. In addition, the implementation has led to inconsistent or limited results<br>
        1 - Needs Major Improvement : The criterion has been inadequately implemented and needs significant improvement. The implementation has led to insignificant or unsatisfactory results.<br>
        0 - Not Implemented : The criterion has not been implemented. Furthermore, no evidence is presented to show that initiatives have been carried out to implement it.
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
        <button type="submit">Submit Survey</button>
    </form>
</div>
</body>
</html>

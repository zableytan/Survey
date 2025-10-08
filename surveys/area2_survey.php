<?php
// area2_survey.php
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
    <title>Area 2 Survey - Quality Assurance</title>
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
            border-left: 4px solid #4f8cff;
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
            background: #4f8cff;
            color: #fff;
            border-color: #4f8cff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(80,120,200,0.10);
        }
        .rating-bar label:hover, .rating-bar label:focus {
            background: #2d6be6;
            color: #fff;
            outline: none;
        }
        button {
            background: linear-gradient(90deg, #4f8cff 60%, #2d6be6 100%);
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
            background: linear-gradient(90deg, #2d6be6 60%, #4f8cff 100%);
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
            border-left: 4px solid #4f8cff;
            padding: 14px 18px 10px 18px;
            margin-bottom: 18px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(80,120,200,0.04);
        }
        .standard-title {
            font-weight: 600;
            color: #2d6be6;
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
        <h2>AREA 2. QUALITY ASSURANCE</h2>
        <div class="rating-guide">
            <strong>Rating Guide:</strong><br>
            5 - Excellent : The practice is exemplary and serves as a model to others.<br>
            4 - Very Good : The criterion has been effectively implemented.<br>
            3 - Good : The criterion has been implemented adequately.<br>
            2 - Needs Minor Improvement : Implemented but needs minor improvement.<br>
            1 - Needs Major Improvement : Implemented inadequately, needs significant improvement.<br>
            0 - Not Implemented : Not implemented, no evidence of initiatives.
        </div>
        <form method="post" action="">
            <div class="section">
                <h3>Sub-area 2.1. Internal Quality Assurance System</h3>
                <div class="standard-box">
                    <div class="standard-title">Standard 6.</div>
                    <div class="standard-desc">Internal Quality Assurance System: The institution has an established internal quality assurance system, with clearly defined policies, procedures, and activities, that implements, evaluates, enhances, and ensures the quality of its educational programs and processes.</div>
                </div>
                <div class="question-card">
                    <label>1. A documented Internal Quality Assurance (IQA) plan is in place with clearly defined policies, procedures, and activities that enable the medical school to develop the quality of its academic and non-academic programs and processes.</label>
                    <?php render_rating('q1'); ?>
                </div>
                <div class="question-card">
                    <label>2. Stakeholders are involved in the formulation, implementation, and evaluation of the IQA plan.</label>
                    <?php render_rating('q2'); ?>
                </div>
                <div class="question-card">
                    <label>3. An IQA structure is established with roles, responsibilities, and accountabilities defined across all levels.</label>
                    <?php render_rating('q3'); ?>
                </div>
                <div class="question-card">
                    <label>4. Adequate resources are committed to supporting the IQA programs.</label>
                    <?php render_rating('q4'); ?>
                </div>
                <div class="question-card">
                    <label>5. Measurable targets and performance indicators are used to measure the performance of the medical school's IQA system.</label>
                    <?php render_rating('q5'); ?>
                </div>
                <div class="question-card">
                    <label>6. Mechanisms for communicating the performance results to concerned stakeholders are in place.</label>
                    <?php render_rating('q6'); ?>
                </div>
                <div class="question-card">
                    <label>7. The implementation of the IQA plan is reviewed annually for continuous improvement.</label>
                    <?php render_rating('q7'); ?>
                </div>
            </div>
            <div class="section">
                <h3>Sub-area 2.2. External Quality Assurance</h3>
                <div class="standard-box">
                    <div class="standard-title">Standard 7.</div>
                    <div class="standard-desc">The institution subjects itself to periodic external assessment designed to be fit for purpose and to validate the effectiveness of its IQA system in terms of regulatory requirements and quality standards.</div>
                </div>
                <div class="question-card">
                    <label>1. The medical school has an established external quality assurance assessment plan to comply with institutional and regulatory requirements.</label>
                    <?php render_rating('q8'); ?>
                </div>
                <div class="question-card">
                    <label>2. The assessment is evidence-based and done by credible and independent external agencies.</label>
                    <?php render_rating('q9'); ?>
                </div>
                <div class="question-card">
                    <label>3. The results of the assessment are communicated to both internal and external stakeholders.</label>
                    <?php render_rating('q10'); ?>
                </div>
                <div class="question-card">
                    <label>4. The assessment results and findings are analyzed and used for improvement.</label>
                    <?php render_rating('q11'); ?>
                </div>
                <div class="question-card">
                    <label>5. The plans and activities for external quality assurance assessments are regularly improved.</label>
                    <?php render_rating('q12'); ?>
                </div>
            </div>
            <button type="submit">Submit Survey</button>
        </form>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Area Selection</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: var(--text-main);
        }

        .container {
            width: 100%;
            max-width: 600px;
            background: var(--card-bg);
            padding: 48px;
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 70px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 24px;
        }

        .selection-info {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .badge {
            background: #f1f5f9;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        .badge.highlight {
            background: #eff6ff;
            color: var(--primary-color);
            border-color: #dbeafe;
        }

        .instruction {
            font-size: 0.95rem;
            color: var(--text-muted);
            margin-top: 16px;
        }

        .survey-grid {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 40px;
        }

        .survey-item {
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            transition: var(--transition);
            color: var(--text-main);
        }

        .survey-item:hover {
            border-color: var(--primary-color);
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.08);
        }

        .survey-item.completed {
            background: #f8fafc;
            border-color: #e2e8f0;
            opacity: 0.7;
            pointer-events: none;
        }

        .area-num {
            font-weight: 800;
            font-size: 0.8rem;
            color: var(--text-muted);
            width: 60px;
            flex-shrink: 0;
        }

        .area-title {
            font-weight: 600;
            font-size: 0.95rem;
            flex-grow: 1;
        }

        .status-icon {
            margin-left: 12px;
            color: #10b981;
        }

        .footer-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .btn-back {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-back:hover {
            color: var(--text-main);
        }

        .btn-logout {
            text-decoration: none;
            padding: 10px 20px;
            background: #fee2e2;
            color: #ef4444;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            transition: var(--transition);
        }

        .btn-logout:hover {
            background: #fecaca;
            transform: translateY(-1px);
        }

        @media (max-width: 640px) {
            .container {
                padding: 32px 20px;
                border-radius: 0;
            }
            body { padding: 0; background: #fff; }
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        session_start();

        $program = '';
        $role = '';

        if (isset($_GET['program']) && !empty($_GET['program'])) {
            $program = htmlspecialchars($_GET['program']);
        }

        if (isset($_GET['role']) && !empty($_GET['role'])) {
            $role = htmlspecialchars($_GET['role']);
        }

        // If program or role are empty, redirect to selection.php
        if (empty($program) || empty($role)) {
            header("Location: selection.php");
            exit();
        }

        // Function to render survey link or disabled text
        function renderSurveyLink($area, $program, $role) {
            $session_flag_name = 'survey_submitted_area' . $area . '_' . md5($program . $role);
            $survey_title = "";
            switch ($area) {
                case 1: $survey_title = "Leadership and Governance"; break;
                case 2: $survey_title = "Quality Assurance"; break;
                case 3: $survey_title = "Resource Management"; break;
                case 4: $survey_title = "Teaching-Learning"; break;
                case 5: $survey_title = "Student Services"; break;
                case 6: $survey_title = "External Relations"; break;
                case 7: $survey_title = "Research"; break;
                case 8: $survey_title = "Results"; break;
            }

            $is_completed = (isset($_SESSION[$session_flag_name]) && $_SESSION[$session_flag_name] === true);
            
            if ($is_completed) {
                echo '
                <div class="survey-item completed">
                    <span class="area-num">AREA ' . $area . '</span>
                    <span class="area-title">' . $survey_title . '</span>
                    <span class="status-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </span>
                </div>';
            } else {
                echo '
                <a href="surveys/area' . $area . '_survey.php?program=' . urlencode($program) . '&role=' . urlencode($role) . '" class="survey-item">
                    <span class="area-num">AREA ' . $area . '</span>
                    <span class="area-title">' . $survey_title . '</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3"><path d="M9 18l6-6-6-6"></path></svg>
                </a>';
            }
        }
        ?>

        <div class="header">
            <img src="DMSF_Logo.png" alt="DMSF Logo" class="logo">
            <h1>PAASCU Self-Survey</h1>
            <div class="selection-info">
                <span class="badge highlight"><?php echo $program; ?></span>
                <span class="badge"><?php echo $role; ?></span>
            </div>
            <p class="instruction">Please select a survey area to begin.</p>
        </div>

        <div class="survey-grid">
            <?php for ($i = 1; $i <= 8; $i++) { renderSurveyLink($i, $program, $role); } ?>
        </div>

        <div class="footer-nav">
            <a href="selection.php" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Change Selection
            </a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </div>
</body>

</html>

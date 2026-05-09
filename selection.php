<?php
session_start();

// Clear all session variables when a new selection is made
if (isset($_GET['program']) || isset($_GET['role'])) {
    session_unset();
    session_destroy();
    session_start(); // Start a new session after destroying the old one
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Program and Role Selection</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-main);
        }

        .container {
            width: 100%;
            max-width: 500px;
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

        .logo-container {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-container img {
            width: 80px;
            height: auto;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
            color: var(--text-main);
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 40px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            margin-left: 4px;
        }

        select {
            appearance: none;
            width: 100%;
            padding: 14px 16px;
            font-size: 1rem;
            font-family: inherit;
            color: var(--text-main);
            background-color: #fff;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 18px;
            border: 1px solid var(--input-border);
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
        }

        select:focus {
            outline: none;
            border-color: var(--input-focus);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        select:hover {
            border-color: var(--text-muted);
        }

        button {
            margin-top: 8px;
            padding: 16px;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
        }

        button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        button:active {
            transform: translateY(0);
        }

        @media (max-width: 640px) {
            .container {
                padding: 32px 24px;
                border-radius: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            
            body {
                padding: 0;
                background: #fff;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="DMSF_Logo.png" alt="DMSF Logo">
        </div>
        <h1>PAASCU Self-Survey</h1>
        <p class="subtitle">Please select your program and role to continue.</p>
        
        <form method="get" action="index.php">
            <div class="form-group">
                <label for="program">Academic Program</label>
                <select name="program" id="program" required>
                    <option value="" disabled selected>Choose a program...</option>
                    <option value="BS Nursing">BS Nursing</option>
                    <option value="BS Biology">BS Biology</option>
                    <option value="BS Midwifery">BS Midwifery</option>
                    <option value="Medicine">Medicine</option>
                </select>
            </div>

            <div class="form-group">
                <label for="role">Your Role</label>
                <select name="role" id="role" required>
                    <option value="" disabled selected>Choose your role...</option>
                    <option value="Student">Student</option>
                    <option value="Full-time Faculty">Full-time Faculty</option>
                    <option value="Part-time Faculty">Part-time Faculty</option>
                    <option value="Student Support Office">Student Support Office</option>
                    <option value="Government Stakeholders">Government Stakeholders</option>
                    <option value="Community Stakeholders">Community Stakeholders</option>
                    <option value="Alumni">Alumni</option>
                </select>
            </div>

            <button type="submit">Begin Survey</button>
        </form>
    </div>
</body>

</html>
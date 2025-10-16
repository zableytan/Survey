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
        label {
            display: block;
            margin-top: 16px;
            margin-bottom: 8px;
            font-weight: 500;
            color: #3b4a5a;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border-radius: 6px;
            border: 1px solid #c3d2f7;
            background-color: #f8fafc;
            color: #3b4a5a;
            font-size: 1rem;
        }
        select:focus {
            outline: none;
            border-color: #186098;
            box-shadow: 0 0 0 2px rgba(24, 96, 152, 0.2);
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
            label { font-size: 0.98rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="DMSF_Logo.png" alt="DMSF Logo" style="display: block; margin: 0 auto 20px auto; max-width: 100%; height: auto; width: 100px;">
        <h2>PAASCU SELF-SURVEY INSTRUMENT</h2>
    <form method="get" action="index.php">
        <label for="program">Program:</label>
        <select name="program" id="program" required>
            <option value="">-- Select Program --</option>
            <option value="BS Nursing">BS Nursing</option>
            <option value="BS Biology">BS Biology</option>
            <option value="BS Midwifery">BS Midwifery</option>
        </select>
        <label for="role">Role:</label>
        <select name="role" id="role" required>
            <option value="">-- Select Role --</option>
            <option value="Student">Student</option>
            <option value="Full-time Faculty">Full-time Faculty</option>
            <option value="Part-time Faculty">Part-time Faculty</option>
            <option value="Student Support Office">Student Support Office</option>
        </select>
        <button type="submit">Proceed</button>
    </form>
    </div>
</body>
</html>
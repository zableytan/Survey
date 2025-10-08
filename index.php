<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Department and Role Selection</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 60px auto; background: #fff; padding: 30px 40px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 24px; }
        label { display: block; margin-top: 16px; margin-bottom: 8px; font-weight: bold; }
        select, button { width: 100%; padding: 10px; margin-bottom: 16px; border-radius: 4px; border: 1px solid #ccc; }
        button { background: #007bff; color: #fff; border: none; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Select Department and Role</h2>
        <form method="post" action="">
            <label for="department">Department:</label>
            <select name="department" id="department" required>
                <option value="">-- Select Department --</option>
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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerbang Masuk - Partlyfe</title>
    <style>
        :root {
            --orange: #E8521A;
            --bg-dark: #0A0A08;
            --surface: #111110;
            --text-main: #F2EFE6;
            --border: rgba(242, 239, 230, 0.08);
        }
        body {
            margin: 0; font-family: system-ui, sans-serif;
            background: var(--bg-dark); color: var(--text-main);
            display: flex; align-items: center; justify-content: center; height: 100vh;
        }
        .login-box {
            background: var(--surface); padding: 3rem; border: 1px solid var(--border);
            border-radius: 4px; width: 100%; max-width: 400px;
            box-shadow: 0 10px 30px rgba(232,82,26,0.05);
        }
        h1 { text-align: center; color: var(--orange); margin-top: 0; letter-spacing: 0.1em; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: #888880; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 0.75rem; box-sizing: border-box;
            background: #1A1A16; border: 1px solid var(--border);
            color: var(--text-main); border-radius: 2px;
        }
        input:focus { outline: none; border-color: var(--orange); }
        .btn {
            width: 100%; padding: 1rem; background: var(--orange);
            color: white; border: none; border-radius: 2px;
            font-size: 1rem; cursor: pointer; font-weight: bold;
        }
        .role-switch {
            display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 2rem;
            padding-bottom: 1.5rem; border-bottom: 1px solid var(--border);
        }
        .role-switch label { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; color: var(--text-main); font-weight: 500; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>PARTLYFE AUTH</h1>
        
        <form onsubmit="handleLogin(event)">
            <div class="role-switch">
                <label><input type="radio" name="role" value="customer" checked> 👤 Pelanggan</label>
                <label><input type="radio" name="role" value="admin"> 👑 Administrator</label>
            </div>
            
            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" placeholder="contoh@gmail.com" required>
            </div>
            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn">MASUK SISTEM</button>
        </form>
    </div>

    <script>
        // Logika Pengalihan Hardcoded tanpa Database Controller
        function handleLogin(e) {
            e.preventDefault();
            const role = document.querySelector('input[name="role"]:checked').value;
            
            if(role === 'admin') {
                window.location.href = '/admin/dashboard'; // Arahkan ke rute Admin UI
            } else {
                window.location.href = '/'; // Arahkan ke rute Customer (Home/Katalog)
            }
        }
    </script>
</body>
</html>
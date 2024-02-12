<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" charset="UTF-8"></script>

    <style>
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px; /* Adjust the gap value as needed */
            color: white;
            font-family: sans-serif;
        }
    </style>
</head>

<body>
    <header>
        <div class="user-info">
                <i class="fas fa-user"></i>
                <?php
            // Check if the 'fname' session variable is set
            if (isset($_SESSION['fname'])) {
                echo '<span>' . htmlspecialchars($_SESSION['fname']) . '</span>';
            } else {
                // Handle the case where the user is not logged in
                echo '<span>Guest</span>';
            }
            ?>            
            </div>        
            <div class="navigation">
            <ul class="menu">
                <li class="menu-item"><a href="#">Home</a></li>
                <li class="menu-item"><a href="../login-signup/form-view-member.php">Anggota</a></li>
                <li class="menu-item"><a href="../login-signup/list-tabungan.php">Tabungan</a></li>
                <li class="menu-item"><a href="../login-signup/list-pinjaman.php">Pinjaman</a></li>
                <li class="menu-item"><a href="../login-signup/print.php">Cetak</a></li>
                <li class="menu-item"><a href="../login-signup/logout.php">Log Out</a></li>
            </ul>
        </div>
    </header>
</body>

</html>

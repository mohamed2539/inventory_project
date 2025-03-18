
<?php
session_start();

// ✅ التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>

    <!-- Tailwind CSS -->
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="../../../assets/css/Animate.css" rel="stylesheet">
    <link href="../../../assets/css/master.css" rel="stylesheet">
    <link href="../../../assets/css/auth.css" rel="stylesheet">
    <link href="../../../assets/css/sidebar.css" rel="stylesheet">
    <!-- أيقونات Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!--<body class="flex justify-center items-center min-h-screen bg-[#f9f9f9]">-->
</head>
<body>
<?php include '../layouts/navbar.php'; ?>


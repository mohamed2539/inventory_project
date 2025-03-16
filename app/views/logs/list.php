<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل العمليات</title>
    <link href="../../../assets/css/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-100">
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">سجل العمليات</h2>
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
        <tr class="bg-gray-200">
            <th class="border p-2">المستخدم</th>
            <th class="border p-2">العملية</th>
            <th class="border p-2">الجدول</th>
            <th class="border p-2">التفاصيل</th>
            <th class="border p-2">التاريخ</th>
        </tr>
        </thead>
        <tbody>
        <?php
        require_once '../../app/models/Log.php';
        $logModel = new Log();
        $logs = $logModel->getRecentLogs();

        foreach ($logs as $log): ?>
            <tr>
                <td class="border p-2"><?php echo $log['username']; ?></td>
                <td class="border p-2"><?php echo ucfirst($log['action']); ?></td>
                <td class="border p-2"><?php echo $log['table_name']; ?></td>
                <td class="border p-2"><?php echo htmlspecialchars($log['details']); ?></td>
                <td class="border p-2"><?php echo $log['created_at']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

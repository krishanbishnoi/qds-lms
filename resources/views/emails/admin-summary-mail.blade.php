<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: left;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .stat-box {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .stat-box h3 {
            margin: 0;
            color: #007bff;
            font-size: 24px;
        }
        .stat-box p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $reminder->entity_type === 'training' ? 'Training' : 'Test' }} Completion Report</h2>
            <p style="margin: 5px 0 0 0;">{{ $reminder->name }}</p>
        </div>
        <div class="content">
            <p>Dear Administrator,</p>

            <p>This is an automated report for the <strong>{{ $reminder->name }}</strong> reminder sent on <strong>{{ now()->format('M d, Y H:i') }}</strong>.</p>

            @if(isset($summary) && !empty($summary))
                <div class="stats">
                    <div class="stat-box">
                        <h3>{{ $summary['total'] ?? 0 }}</h3>
                        <p>Total Participants</p>
                    </div>
                    <div class="stat-box">
                        <h3>{{ $summary['completed'] ?? 0 }}</h3>
                        <p>Completed</p>
                    </div>
                    <div class="stat-box">
                        <h3>{{ $summary['pending'] ?? 0 }}</h3>
                        <p>Pending</p>
                    </div>
                </div>
            @endif

            <p style="margin-top: 20px; color: #666;">
                A detailed CSV report with user-wise completion status is attached to this email.
            </p>

            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                Please review the attached report for complete details. If you have any questions, please contact system administration.
            </p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} LMS System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

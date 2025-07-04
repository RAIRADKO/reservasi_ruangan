<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Notifikasi Sistem')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            width: 100%;
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid #e1e8ed;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            animation: float 20s linear infinite;
        }
        
        @keyframes float {
            0% { transform: translateX(-50px) translateY(-50px); }
            100% { transform: translateX(50px) translateY(50px); }
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }
        
        .header-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
            position: relative;
            z-index: 1;
        }
        
        .content {
            padding: 40px 30px;
            color: #2c3e50;
            background: #ffffff;
        }
        
        .content h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: 600;
        }
        
        .content p {
            margin-bottom: 16px;
            font-size: 16px;
            color: #34495e;
        }
        
        .info-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .info-card h3 {
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .info-table th,
        .info-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .info-table tr:hover {
            background-color: #e3f2fd;
            transition: background-color 0.3s ease;
        }
        
        .info-table td:first-child {
            font-weight: 600;
            color: #2c3e50;
            width: 35%;
        }
        
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        
        .status-approved {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .status-rejected {
            background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
            color: white;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        
        .button {
            display: inline-block;
            padding: 16px 32px;
            margin: 25px 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: #ffffff;
        }
        
        .rejection-box {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            border-left: 4px solid #e53e3e;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .rejection-box p {
            margin: 0;
            font-style: italic;
            color: #2d3748;
        }
        
        .info-list {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .info-list h4 {
            margin-bottom: 15px;
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
        }
        
        .info-list ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .info-list li {
            margin-bottom: 10px;
            color: #34495e;
            font-size: 15px;
        }
        
        .footer {
            padding: 30px;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-top: 1px solid #dee2e6;
        }
        
        .footer p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }
        
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
            text-align: left;
        }
        
        .signature p {
            margin: 5px 0;
        }
        
        /* Responsive Design */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            .container {
                margin: 0;
                border-radius: 0;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .info-table th,
            .info-table td {
                padding: 12px 8px;
                font-size: 14px;
            }
            
            .button {
                padding: 14px 28px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="header-icon">@yield('header_icon', '🏢')</span>
            <h1>@yield('header', 'Sistem Reservasi Ruangan')</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>
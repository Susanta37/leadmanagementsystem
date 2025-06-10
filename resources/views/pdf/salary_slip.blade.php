<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salary Slip - {{ \Carbon\Carbon::parse($slip->month)->format('F Y') }}</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #475569;
            --border-color: #e2e8f0;
            --background-color: #f8fafc;
            --text-color: #334155;
        }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: var(--text-color);
            line-height: 1.6;
            background-color: var(--background-color);
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 30px;
        }
        
        .logo {
            max-height: 60px;
        }
        
        .company-info {
            text-align: right;
            font-size: 14px;
        }
        
        .slip-title {
            text-align: center;
            color: var(--primary-color);
            font-size: 24px;
            margin: 20px 0;
            font-weight: 600;
        }
        
        .employee-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 15px;
            background-color: var(--background-color);
            border-radius: 8px;
        }
        
        .employee-details div {
            flex: 1;
        }
        
        .detail-label {
            font-weight: bold;
            color: var(--secondary-color);
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .detail-value {
            font-size: 16px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background-color: var(--background-color);
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .amount-column {
            text-align: right;
        }
        
        .total-row {
            font-weight: bold;
            border-top: 2px solid var(--border-color);
            background-color: var(--background-color);
        }
        
        .total-row td {
            padding: 15px;
            color: var(--primary-color);
            font-size: 18px;
        }
        
        .section-title {
            color: var(--primary-color);
            font-size: 18px;
            margin: 25px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid var(--border-color);
            display: flex;
            justify-content: space-between;
        }
        
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            width: 45%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid var(--border-color);
            margin-top: 40px;
            padding-top: 10px;
        }
        
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: var(--background-color);
            border-radius: 8px;
            font-size: 14px;
        }
        
        @media print {
            body {
                background-color: white;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <img src="{{ asset('logo.png') }}" alt="Kredipal Logo" class="logo">
            </div>
            <div class="company-info">
                <h2>Kredipal</h2>
                <p>Phone: +919090759555</p>
                <p>Email: info@kredipal.com</p>
            </div>
        </div>
        
        <h1 class="slip-title">Salary Slip - {{ \Carbon\Carbon::parse($slip->month)->format('F Y') }}</h1>
        
        <div class="employee-details">
            <div>
                <div class="detail-label">Employee Name</div>
                <div class="detail-value">{{ $slip->user->name }}</div>
                
                <div class="detail-label" style="margin-top: 10px;">Employee ID</div>
                <div class="detail-value">{{ $slip->user->id ?? 'N/A' }}</div>
            </div>
            
            <div>
                <div class="detail-label">Email</div>
                <div class="detail-value">{{ $slip->user->email }}</div>
                
                <div class="detail-label" style="margin-top: 10px;">Department</div>
                <div class="detail-value">{{ $slip->user->department ?? 'N/A' }}</div>
            </div>
            
            <div>
                <div class="detail-label">Payment Date</div>
                <div class="detail-value">{{ \Carbon\Carbon::now()->format('d M, Y') }}</div>
                
                <div class="detail-label" style="margin-top: 10px;">Pay Period</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($slip->month)->startOfMonth()->format('d M') }} - {{ \Carbon\Carbon::parse($slip->month)->endOfMonth()->format('d M, Y') }}</div>
            </div>
        </div>
        
        <h3 class="section-title">Earnings</h3>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount-column">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary</td>
                    <td class="amount-column">{{ number_format($slip->basic, 2) }}</td>
                </tr>
                <tr>
                    <td>House Rent Allowance (HRA)</td>
                    <td class="amount-column">{{ number_format($slip->hra, 2) }}</td>
                </tr>
                <tr>
                    <td>Special Allowance</td>
                    <td class="amount-column">{{ number_format($slip->allowance, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Gross Earnings</strong></td>
                    <td class="amount-column"><strong>{{ number_format($slip->basic + $slip->hra + $slip->allowance, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        
        <h3 class="section-title">Deductions</h3>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount-column">Amount (₹)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Deductions</td>
                    <td class="amount-column">{{ number_format($slip->deductions, 2) }}</td>
                </tr>
            </tbody>
        </table>
        
        <table>
            <tbody>
                <tr class="total-row">
                    <td><strong>Net Salary</strong></td>
                    <td class="amount-column"><strong>₹ {{ number_format($slip->net_salary, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        
        <div class="notes">
            <strong>Note:</strong> This is a computer-generated salary slip and does not require a physical signature.
            For any queries regarding your salary, please contact the HR department.
        </div>
        
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Employee Signature</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Authorized Signature</div>
            </div>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Kredipal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

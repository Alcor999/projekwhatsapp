<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Blast Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #020617;
            --bg-card: #0F172A;
            --bg-elevated: #1E293B;
            --border-subtle: #1E293B;
            --border-focus: #3B82F6;
            --text-main: #F8FAFC;
            --text-muted: #94A3B8;
            --accent: #25D366;
            --accent-hover: #1EAD54;
            --accent-warning: #F59E0B;
            --accent-warning-hover: #D97706;
            --danger: #EF4444;
            --danger-hover: #DC2626;
            --font-ui: 'Inter', system-ui, sans-serif;
            --font-code: 'Fira Code', monospace;
            --radius-md: 10px;
            --radius-lg: 16px;
            --shadow-card: 0 4px 20px rgba(0,0,0,0.4);
            --shadow-glow: 0 0 15px rgba(37, 211, 102, 0.2);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-ui);
            background-color: var(--bg-base);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: 280px;
            background-color: var(--bg-card);
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            padding: 24px 20px;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 50;
            flex-shrink: 0;
        }
        .brand { display: flex; align-items: center; gap: 12px; font-size: 20px; font-weight: 700; color: var(--text-main); margin-bottom: 40px; padding: 0 8px; }
        .menu { display: flex; flex-direction: column; gap: 8px; }
        .menu-item { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: var(--radius-md); text-decoration: none; color: var(--text-muted); font-weight: 500; font-size: 15px; transition: var(--transition); border: 1px solid transparent; }
        .menu-item svg { width: 20px; height: 20px; stroke-width: 2; flex-shrink: 0; }
        .menu-item:hover { background-color: var(--bg-elevated); color: var(--text-main); cursor: pointer; }
        .menu-item.active { background-color: rgba(37, 211, 102, 0.08); color: var(--accent); border-color: rgba(37, 211, 102, 0.2); }
        .menu-item.active svg { stroke: var(--accent); }

        /* ── MAIN ── */
        .main-wrapper { flex: 1; padding: 40px; max-width: 1200px; margin: 0 auto; width: 100%; }
        .page-header { margin-bottom: 32px; }
        .page-title { font-size: 32px; font-weight: 700; letter-spacing: -0.02em; }
        .page-subtitle { color: var(--text-muted); margin-top: 6px; font-size: 15px; }

        .grid { display: grid; gap: 24px; }
        .grid-2 { grid-template-columns: 1fr 1fr; }
        .grid-3 { grid-template-columns: repeat(3, 1fr); margin-bottom: 24px; }

        .card { background-color: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); overflow: hidden; display: flex; flex-direction: column; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid var(--border-subtle); display: flex; align-items: center; gap: 10px; font-size: 18px; font-weight: 600; }
        .card-body { padding: 24px; flex: 1; display: flex; flex-direction: column; }

        /* ── STATS ── */
        .stat-card { background: linear-gradient(145deg, var(--bg-card) 0%, var(--bg-base) 100%); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 20px; position: relative; overflow: hidden; }
        .stat-card::after { content: ''; position: absolute; top: -30px; right: -30px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(37,211,102,0.06) 0%, transparent 70%); border-radius: 50%; }
        .stat-label { color: var(--text-muted); font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { font-size: 32px; font-weight: 700; margin-top: 8px; color: var(--text-main); }
        .stat-sub { font-size: 14px; color: var(--text-muted); margin-top: 4px; }

        /* ── FORMS ── */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: var(--text-main); }
        .input-control { width: 100%; background-color: var(--bg-base); border: 1px solid var(--border-subtle); color: var(--text-main); border-radius: var(--radius-md); padding: 12px 16px; font-size: 15px; font-family: var(--font-ui); transition: var(--transition); }
        .input-control:focus { outline: none; border-color: var(--border-focus); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
        textarea.input-control { min-height: 200px; resize: vertical; line-height: 1.5; font-family: var(--font-code); font-size: 14px; padding: 16px; }

        code { font-family: var(--font-code); background-color: var(--bg-base); padding: 3px 6px; border-radius: 6px; font-size: 13px; color: var(--accent); border: 1px solid var(--border-subtle); }
        .hint { font-size: 13px; color: var(--text-muted); margin-top: 8px; line-height: 1.5; }

        /* ── BUTTONS ── */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 24px; font-size: 15px; font-weight: 600; border-radius: var(--radius-md); border: none; cursor: pointer; transition: var(--transition); font-family: var(--font-ui); text-decoration: none; }
        .btn svg { width: 18px; height: 18px; }
        .btn-primary { background-color: var(--accent); color: #020617; }
        .btn-primary:hover { background-color: var(--accent-hover); transform: translateY(-2px); box-shadow: var(--shadow-glow); }
        .btn-warning { background-color: var(--accent-warning); color: #020617; }
        .btn-warning:hover { background-color: var(--accent-warning-hover); transform: translateY(-2px); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2); }
        .btn-danger { background-color: var(--danger); color: #fff; }
        .btn-danger:hover { background-color: var(--danger-hover); transform: translateY(-2px); }
        .btn-success { background-color: var(--accent); color: #020617; }
        .btn-success:hover { background-color: var(--accent-hover); transform: translateY(-2px); box-shadow: var(--shadow-glow); }
        .btn-sm { padding: 8px 14px; font-size: 13px; }
        .btn-ghost { background-color: transparent; color: var(--text-muted); border: 1px solid var(--border-subtle); }
        .btn-ghost:hover { background-color: var(--bg-elevated); color: var(--text-main); }
        .mt-4 { margin-top: 16px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .animate-spin { animation: spin 1s linear infinite; }
        .gap-2 { display: flex; gap: 8px; align-items: center; }

        /* ── TABLES ── */
        .table-container { border: 1px solid var(--border-subtle); border-radius: var(--radius-md); overflow: hidden; background-color: var(--bg-base); }
        .table-scroll { max-height: 500px; overflow-y: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead { position: sticky; top: 0; background-color: var(--bg-elevated); z-index: 10; }
        thead th { padding: 14px 16px; font-size: 13px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-subtle); }
        tbody td { padding: 14px 16px; font-size: 14px; border-bottom: 1px solid var(--border-subtle); color: var(--text-main); }
        tbody tr { transition: var(--transition); }
        tbody tr:hover { background-color: rgba(255,255,255,0.02); }
        tbody tr:last-child td { border-bottom: none; }
        .empty-state { padding: 40px 20px; text-align: center; color: var(--text-muted); }

        /* ── ALERTS ── */
        .alert { display: flex; align-items: flex-start; gap: 12px; padding: 16px; border-radius: var(--radius-md); margin-bottom: 24px; font-size: 14px; line-height: 1.5; animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background-color: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); color: #86efac; }
        .alert-error { background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5; }
        .alert ul { margin-top: 4px; margin-left: 20px; }

        /* ── CHECKBOX ── */
        .checkbox-wrapper { display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none; }
        .checkbox-wrapper input { appearance: none; width: 18px; height: 18px; border: 2px solid var(--text-muted); border-radius: 4px; background-color: transparent; cursor: pointer; position: relative; transition: var(--transition); flex-shrink: 0; }
        .checkbox-wrapper input:checked { background-color: var(--accent); border-color: var(--accent); }
        .checkbox-wrapper input:checked::after { content: ''; position: absolute; left: 5px; top: 2px; width: 4px; height: 8px; border: solid #020617; border-width: 0 2px 2px 0; transform: rotate(45deg); }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding: 12px 16px; background-color: var(--bg-base); border-radius: var(--radius-md); border: 1px solid var(--border-subtle); }
        .badge { background: var(--bg-elevated); padding: 4px 10px; border-radius: 99px; font-size: 12px; font-weight: 600; color: var(--text-main); }

        .footer-action { margin-top: 24px; display: flex; justify-content: flex-end; }

        /* ── MODAL ── */
        .modal-overlay {
            display: none; position: fixed; inset: 0; background-color: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px); z-index: 1000; justify-content: center; align-items: center;
            animation: fadeIn 0.2s ease-out;
        }
        .modal-overlay.open { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal {
            background-color: var(--bg-card); border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg); width: 100%; max-width: 520px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5); animation: slideUp 0.25s ease-out;
            margin: 20px;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border-subtle); display: flex; justify-content: space-between; align-items: center; font-size: 18px; font-weight: 600; }
        .modal-body { padding: 24px; }
        .modal-footer { padding: 16px 24px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; gap: 12px; }
        .modal-close { background: none; border: none; color: var(--text-muted); cursor: pointer; padding: 4px; border-radius: 6px; transition: var(--transition); }
        .modal-close:hover { color: var(--text-main); background-color: var(--bg-elevated); }

        /* ── RESPONSIVE ── */
        @media (max-width: 1024px) {
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
            .grid-2 { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; padding: 16px; position: relative; border-right: none; border-bottom: 1px solid var(--border-subtle); }
            .brand { margin-bottom: 16px; }
            .menu { flex-direction: row; overflow-x: auto; padding-bottom: 8px; scrollbar-width: none; }
            .menu::-webkit-scrollbar { display: none; }
            .menu-item { white-space: nowrap; padding: 10px 14px; }
            .main-wrapper { padding: 20px; }
            .grid-3 { grid-template-columns: 1fr; }
            .card-body, .card-header { padding: 16px; }
            .toolbar { flex-direction: column; align-items: flex-start; gap: 12px; }
            .footer-action { justify-content: stretch; }
            .btn { width: 100%; }
            .page-title { font-size: 24px; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            WA Panel Pro
        </div>
        <nav class="menu">
            <a href="{{ route('customers.master') }}" class="menu-item {{ $page === 'master-customer' ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Master Customer
            </a>
            <a href="{{ route('documents.master') }}" class="menu-item {{ $page === 'master-dokumen' ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Master Dokumen
            </a>
            <a href="{{ route('messages.menu') }}" class="menu-item {{ $page === 'kirim-pesan' ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Kirim Pesan
            </a>
        </nav>
    </aside>

    <main class="main-wrapper">
        @if (session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <strong>Terdapat beberapa kesalahan:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{--           MASTER CUSTOMER PAGE              --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if ($page === 'master-customer')
            <header class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                <div>
                    <h1 class="page-title">Master Customer</h1>
                    <p class="page-subtitle">Kelola database pelanggan untuk kebutuhan broadcast WhatsApp.</p>
                </div>
                <button class="btn btn-primary" onclick="openModal('addCustomerModal')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                    Tambah Customer
                </button>
            </header>

            <div class="grid-3">
                <div class="stat-card">
                    <div class="stat-label">Total Customer</div>
                    <div class="stat-value">{{ number_format($customers->count()) }}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Format Import</div>
                    <div class="stat-value" style="font-size: 24px; margin-top:14px;">Excel / CSV</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Kolom Wajib</div>
                    <div class="stat-sub" style="margin-top: 14px;"><code>nomor hp</code>, <code>nama debitur</code>, <code>nomor kontrak</code></div>
                </div>
            </div>

            {{-- Import Card --}}
            <section class="card" style="margin-bottom: 24px;">
                <header class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Import Data Customer (Excel / CSV)
                </header>
                <div class="card-body" style="flex-direction: row; align-items: center; gap: 16px; flex-wrap: wrap;">
                    <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap; flex: 1;">
                        @csrf
                        <input class="input-control" type="file" name="customer_file" accept=".xlsx,.xls,.csv" required style="max-width: 400px;">
                        <button class="btn btn-warning" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Import
                        </button>
                        <span class="hint" style="margin-top: 0;">Upload untuk menambah / update otomatis berdasarkan nomor kontrak atau nomor HP.</span>
                    </form>
                </div>
            </section>

            {{-- Customer Table Card --}}
            <section class="card">
                <header class="card-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Data Customer ({{ $customers->count() }})
                </header>
                <div class="card-body" style="padding: 0;">
                    <div class="table-container table-scroll" style="border: none; border-radius: 0;">
                        <table>
                            <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Nomor HP</th>
                                <th>Nama Debitur</th>
                                <th>No. Kontrak</th>
                                <th>Unit Kendaraan</th>
                                <th style="width: 160px; text-align: center;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($customers as $index => $customer)
                                <tr>
                                    <td style="color: var(--text-muted);">{{ $index + 1 }}</td>
                                    <td style="font-family: var(--font-code); font-size: 13px;">{{ $customer->phone_number }}</td>
                                    <td style="font-weight: 500;">{{ $customer->debtor_name }}</td>
                                    <td style="color: var(--text-muted); font-size: 13px;">{{ $customer->contract_number }}</td>
                                    <td style="color: var(--text-muted); font-size: 13px;">{{ $customer->vehicle_unit ?? '-' }}</td>
                                    <td>
                                        <div class="gap-2" style="justify-content: center;">
                                            <button class="btn btn-ghost btn-sm" onclick="editCustomer({{ $customer->id }}, '{{ addslashes($customer->phone_number) }}', '{{ addslashes($customer->debtor_name) }}', '{{ addslashes($customer->contract_number) }}', '{{ addslashes($customer->vehicle_unit ?? '') }}')" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Hapus customer {{ addslashes($customer->debtor_name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">Database masih kosong. Silakan import data atau tambah manual.</div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- Add Customer Modal --}}
            <div id="addCustomerModal" class="modal-overlay" onclick="closeModalOnOverlay(event, 'addCustomerModal')">
                <div class="modal">
                    <div class="modal-header">
                        Tambah Customer Baru
                        <button class="modal-close" onclick="closeModal('addCustomerModal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">Nomor HP</label>
                                <input class="input-control" type="text" name="phone_number" placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Debitur</label>
                                <input class="input-control" type="text" name="debtor_name" placeholder="Nama lengkap" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor Kontrak</label>
                                <input class="input-control" type="text" name="contract_number" placeholder="Nomor kontrak unik" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Unit Kendaraan</label>
                                <input class="input-control" type="text" name="vehicle_unit" placeholder="Unit kendaraan (opsional)">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-ghost" onclick="closeModal('addCustomerModal')">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Edit Customer Modal --}}
            <div id="editCustomerModal" class="modal-overlay" onclick="closeModalOnOverlay(event, 'editCustomerModal')">
                <div class="modal">
                    <div class="modal-header">
                        Edit Customer
                        <button class="modal-close" onclick="closeModal('editCustomerModal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <form id="editCustomerForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">Nomor HP</label>
                                <input class="input-control" type="text" name="phone_number" id="editPhone" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nama Debitur</label>
                                <input class="input-control" type="text" name="debtor_name" id="editName" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nomor Kontrak</label>
                                <input class="input-control" type="text" name="contract_number" id="editContract" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Unit Kendaraan</label>
                                <input class="input-control" type="text" name="vehicle_unit" id="editVehicle">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-ghost" onclick="closeModal('editCustomerModal')">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{--           MASTER DOKUMEN PAGE               --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if ($page === 'master-dokumen')
            <header class="page-header">
                <h1 class="page-title">Master Dokumen</h1>
                <p class="page-subtitle">Upload file PDF untuk dilampirkan pada pesan broadcast Anda.</p>
            </header>

            <div class="grid grid-2">
                <section class="card">
                    <header class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
                        Upload Dokumen PDF
                    </header>
                    <div class="card-body">
                        <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Judul Dokumen</label>
                                <input class="input-control" type="text" name="title" placeholder="Contoh: Surat Pelunasan" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">File PDF</label>
                                <input class="input-control" type="file" name="document_file" accept=".pdf" required>
                                <div class="hint">Hanya format PDF, maksimal ukuran 10 MB per file. Jika judul atau nama file sama, data akan di-update.</div>
                            </div>
                            <button class="btn btn-warning mt-4" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Upload ke Master Dokumen
                            </button>
                        </form>
                    </div>
                </section>

                <section class="card">
                    <header class="card-header">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        Daftar Dokumen ({{ $documents->count() }})
                    </header>
                    <div class="card-body" style="padding: 0;">
                        <div class="table-container table-scroll" style="border: none; border-radius: 0;">
                            <table>
                                <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Judul</th>
                                    <th>Nama File</th>
                                    <th style="width: 160px; text-align: center;">Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($documents as $index => $document)
                                    <tr>
                                        <td style="color: var(--text-muted);">{{ $index + 1 }}</td>
                                        <td style="font-weight: 500;">{{ $document->title }}</td>
                                        <td style="color: var(--text-muted); font-size: 13px;">{{ $document->original_name }}</td>
                                        <td>
                                            <div class="gap-2" style="justify-content: center;">
                                                <button class="btn btn-ghost btn-sm" onclick="editDocument({{ $document->id }}, '{{ addslashes($document->title) }}')" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                    Edit
                                                </button>
                                                <form action="{{ route('documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Hapus dokumen {{ addslashes($document->title) }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">Belum ada dokumen. Upload PDF pertama Anda.</div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Edit Document Modal --}}
            <div id="editDocumentModal" class="modal-overlay" onclick="closeModalOnOverlay(event, 'editDocumentModal')">
                <div class="modal">
                    <div class="modal-header">
                        Edit Dokumen
                        <button class="modal-close" onclick="closeModal('editDocumentModal')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <form id="editDocumentForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="form-label">Judul Dokumen</label>
                                <input class="input-control" type="text" name="title" id="editDocTitle" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Ganti File PDF (Opsional)</label>
                                <input class="input-control" type="file" name="document_file" accept=".pdf">
                                <div class="hint">Kosongkan jika hanya ingin mengubah judul.</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-ghost" onclick="closeModal('editDocumentModal')">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- ═══════════════════════════════════════════ --}}
        {{--           KIRIM PESAN PAGE                  --}}
        {{-- ═══════════════════════════════════════════ --}}
        @if ($page === 'kirim-pesan')
            <header class="page-header">
                <h1 class="page-title">Blast Notifikasi</h1>
                <p class="page-subtitle">Kirim pesan WhatsApp otomatis ke pelanggan yang dipilih.</p>
            </header>

            <section class="card">
                <div class="card-body">
                    <form action="{{ route('notifications.send') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label" style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 8px; align-items: center;">
                                <span>Pesan Template</span>
                                <span style="font-weight: normal; color: var(--text-muted); font-size: 13px;">Placeholder: <code>{nama_debitur}</code> <code>{nomor_kontrak}</code> <code>{nomor_hp}</code> <code>{unit_kendaraan}</code></span>
                            </label>
                            <div style="position: relative;">
                                <textarea class="input-control" name="template" id="templateTextarea" required style="min-height: 240px; padding-bottom: 52px;">{{ old('template', $defaultTemplate) }}</textarea>
                                <button type="button" id="saveTemplateBtn" class="btn btn-warning btn-sm" style="position: absolute; bottom: 12px; right: 12px; font-size: 12px; padding: 6px 14px; border-radius: var(--radius-md); z-index: 10; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.5);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Simpan Template
                                </button>
                            </div>
                        </div>

                        <div class="form-group" style="background: var(--bg-base); padding: 16px; border-radius: var(--radius-md); border: 1px solid var(--border-focus);">
                            <label class="form-label" style="color: var(--accent);">Lampiran PDF (Opsional)</label>
                            <select name="document_id" class="input-control" style="background-color: var(--bg-card);">
                                <option value="">-- Tanpa Lampiran --</option>
                                @foreach ($documents as $document)
                                    <option value="{{ $document->id }}" {{ (string) old('document_id') === (string) $document->id ? 'selected' : '' }}>
                                        {{ $document->title }} ({{ $document->original_name }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="hint">Jika dipilih, PDF ini akan ikut dikirim bersama pesan WhatsApp.</div>
                        </div>

                        <div class="toolbar mt-4">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" id="selectAll">
                                <span style="font-weight: 500;">Pilih Semua Customer</span>
                            </label>
                            <div class="badge"><span id="selectedCount">0</span> Dipilih</div>
                        </div>

                        <div class="table-container table-scroll">
                            <table>
                                <thead>
                                <tr>
                                    <th style="width: 60px; text-align: center;">Pilih</th>
                                    <th>Nomor HP</th>
                                    <th>Nama Debitur</th>
                                    <th>No. Kontrak</th>
                                    <th>Unit Kendaraan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td style="text-align: center;">
                                            <label class="checkbox-wrapper" style="justify-content: center;">
                                                <input
                                                    type="checkbox"
                                                    name="customer_ids[]"
                                                    value="{{ $customer->id }}"
                                                    class="customer-check"
                                                    {{ in_array($customer->id, old('customer_ids', [])) ? 'checked' : '' }}
                                                >
                                            </label>
                                        </td>
                                        <td style="font-family: var(--font-code); font-size: 13px;">{{ $customer->phone_number }}</td>
                                        <td style="font-weight: 500;">{{ $customer->debtor_name }}</td>
                                        <td style="color: var(--text-muted); font-size: 13px;">{{ $customer->contract_number }}</td>
                                        <td style="color: var(--text-muted); font-size: 13px;">{{ $customer->vehicle_unit ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">Belum ada data customer</div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="footer-action">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                                Kirim Pesan ke Customer Terpilih
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        @endif
    </main>

    <script>
        // ── Modal Helpers ──
        function openModal(id) {
            document.getElementById(id).classList.add('open');
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }
        function closeModalOnOverlay(event, id) {
            if (event.target === event.currentTarget) closeModal(id);
        }

        // ── Customer CRUD ──
        function editCustomer(id, phone, name, contract, vehicle) {
            const form = document.getElementById('editCustomerForm');
            form.action = '/customers/' + id;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editName').value = name;
            document.getElementById('editContract').value = contract;
            document.getElementById('editVehicle').value = vehicle;
            openModal('editCustomerModal');
        }

        // ── Document CRUD ──
        function editDocument(id, title) {
            const form = document.getElementById('editDocumentForm');
            form.action = '/documents/' + id;
            document.getElementById('editDocTitle').value = title;
            openModal('editDocumentModal');
        }

        // ── Select All / Counter ──
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checks = Array.from(document.querySelectorAll('.customer-check'));
            const selectedCount = document.getElementById('selectedCount');

            function updateCounter() {
                if (!selectedCount) return;
                const total = checks.filter((item) => item.checked).length;
                selectedCount.textContent = total;
                if (selectAll) {
                    selectAll.checked = checks.length > 0 && total === checks.length;
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checks.forEach((item) => item.checked = this.checked);
                    updateCounter();
                });
            }
            checks.forEach((item) => item.addEventListener('change', updateCounter));
            updateCounter();
        });

        // ── Save Template AJAX ──
        const saveTemplateBtn = document.getElementById('saveTemplateBtn');
        if (saveTemplateBtn) {
            saveTemplateBtn.addEventListener('click', function() {
                const template = document.getElementById('templateTextarea').value;
                const btn = this;
                const originalHtml = btn.innerHTML;

                btn.disabled = true;
                btn.innerHTML = `<svg class="animate-spin" style="margin-right: 4px;" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg> Menyimpan...`;

                fetch('{{ route('template.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ template: template })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.className = "btn btn-success btn-sm";
                        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Tersimpan!`;
                        setTimeout(() => {
                            btn.className = "btn btn-warning btn-sm";
                            btn.innerHTML = originalHtml;
                            btn.disabled = false;
                        }, 2000);
                    } else {
                        alert('Gagal menyimpan template: ' + (data.message || 'Error tidak diketahui'));
                        btn.innerHTML = originalHtml;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menyimpan template.');
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                });
            });
        }

        // ── Close modal on Escape key ──
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
            }
        });
    </script>
</body>
</html>

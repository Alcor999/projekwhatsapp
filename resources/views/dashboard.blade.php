<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Alert Center - Professional Dashboard</title>
    <style>
        :root {
            --bg: #f3f6fb;
            --card: #ffffff;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #dbe3ee;
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --danger-bg: #fee2e2;
            --danger-text: #991b1b;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --radius: 14px;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Inter", "Segoe UI", Arial, sans-serif;
            background: radial-gradient(circle at top right, #e8f0ff 0%, var(--bg) 45%);
            color: var(--ink);
        }

        .container { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .topbar {
            display: flex; justify-content: space-between; align-items: center;
            gap: 16px; margin-bottom: 20px;
        }
        .title h1 { margin: 0; font-size: 28px; }
        .title p { margin: 8px 0 0; color: var(--muted); }
        .badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: #eaf2ff; color: #1e3a8a; padding: 8px 12px;
            border-radius: 999px; font-size: 13px; font-weight: 600;
        }
        .dot { width: 8px; height: 8px; border-radius: 999px; background: #2563eb; }

        .flash { margin-bottom: 16px; padding: 12px 14px; border-radius: 10px; font-size: 14px; }
        .flash.success { background: var(--success-bg); color: var(--success-text); }
        .flash.error { background: var(--danger-bg); color: var(--danger-text); }
        .flash ul { margin: 0; padding-left: 18px; }

        .stats {
            display: grid; grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px; margin-bottom: 16px;
        }
        .stat-card {
            background: var(--card); border: 1px solid var(--line); border-radius: var(--radius);
            padding: 14px; box-shadow: var(--shadow);
        }
        .stat-card .label { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
        .stat-card .value { font-size: 24px; font-weight: 700; }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1.6fr;
            gap: 18px;
            align-items: start;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .card-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            background: #f8fbff;
        }
        .card-head h2 { margin: 0; font-size: 17px; }
        .card-head p { margin: 6px 0 0; color: var(--muted); font-size: 13px; }
        .card-body { padding: 16px 18px; }

        .input, .textarea {
            width: 100%; border: 1px solid #cbd5e1; border-radius: 10px;
            padding: 10px 12px; font-size: 14px; color: #0f172a; background: #fff;
        }
        .textarea { min-height: 220px; resize: vertical; line-height: 1.5; }
        .hint { margin-top: 8px; font-size: 12px; color: var(--muted); }
        code {
            background: #eff6ff; border: 1px solid #bfdbfe; color: #1e3a8a;
            border-radius: 6px; padding: 2px 6px; font-size: 12px;
        }

        .btn {
            border: none; border-radius: 10px; padding: 10px 14px;
            font-size: 14px; font-weight: 600; cursor: pointer; color: #fff;
        }
        .btn.primary { background: var(--primary); }
        .btn.primary:hover { background: var(--primary-dark); }

        .toolbar {
            margin: 12px 0; display: flex; justify-content: space-between;
            align-items: center; gap: 10px; color: var(--muted); font-size: 13px;
        }
        .checkbox-inline { display: inline-flex; align-items: center; gap: 7px; cursor: pointer; }
        .table-wrap { border: 1px solid var(--line); border-radius: 12px; overflow: auto; max-height: 350px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        thead th {
            position: sticky; top: 0; background: #f8fafc;
            border-bottom: 1px solid var(--line); color: #334155;
            text-align: left; padding: 10px 12px; font-weight: 600;
        }
        tbody td { border-top: 1px solid #edf2f7; padding: 10px 12px; }
        tbody tr:hover { background: #f8fbff; }
        .empty { text-align: center; color: var(--muted); padding: 20px; }
        .submit-row { margin-top: 14px; display: flex; justify-content: flex-end; }

        @media (max-width: 960px) {
            .stats { grid-template-columns: 1fr; }
            .grid { grid-template-columns: 1fr; }
            .topbar { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div class="title">
                <h1>WhatsApp Alert Center</h1>
                <p>Dashboard notifikasi customer via Fonnte untuk operasional harian.</p>
            </div>
            <span class="badge"><span class="dot"></span> Siap Kirim Notifikasi</span>
        </div>

        @if (session('success'))
            <div class="flash success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash error">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="flash error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <div class="label">Total Customer Tersimpan</div>
                <div class="value">{{ $customers->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="label">Kolom Wajib Import</div>
                <div class="value" style="font-size:16px;">Nomor HP, Nama Debitur, Nomor Kontrak</div>
            </div>
            <div class="stat-card">
                <div class="label">Placeholder Template</div>
                <div class="value" style="font-size:16px;">{nama_debitur}, {nomor_kontrak}, {nomor_hp}</div>
            </div>
        </div>

        <div class="grid">
            <section class="card">
                <div class="card-head">
                    <h2>Import Master Customer</h2>
                    <p>Upload file Excel/CSV untuk menambahkan atau memperbarui data customer otomatis.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input class="input" type="file" name="customer_file" accept=".xlsx,.xls,.csv" required>
                        <div class="hint">Pastikan nama kolom file sesuai standar sebelum upload.</div>
                        <div style="margin-top: 14px;">
                            <button type="submit" class="btn primary">Import Data Customer</button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="card">
                <div class="card-head">
                    <h2>Kirim Notifikasi WhatsApp</h2>
                    <p>Tulis template pesan, pilih penerima, lalu kirim secara massal.</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('notifications.send') }}" method="POST" id="sendForm">
                        @csrf
                        <textarea class="textarea" name="template" rows="12" required>{{ old('template', $defaultTemplate) }}</textarea>
                        <div class="hint">
                            Gunakan placeholder:
                            <code>{nama_debitur}</code>,
                            <code>{nomor_kontrak}</code>,
                            <code>{nomor_hp}</code>
                        </div>

                        <div class="toolbar">
                            <label class="checkbox-inline">
                                <input type="checkbox" id="selectAll">
                                Pilih semua customer
                            </label>
                            <span id="selectedCount">0 dipilih</span>
                        </div>

                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Pilih</th>
                                        <th>Nomor HP</th>
                                        <th>Nama Debitur</th>
                                        <th>Nomor Kontrak</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td>
                                                <input
                                                    type="checkbox"
                                                    name="customer_ids[]"
                                                    value="{{ $customer->id }}"
                                                    class="customer-check"
                                                    {{ in_array($customer->id, old('customer_ids', [])) ? 'checked' : '' }}
                                                >
                                            </td>
                                            <td>{{ $customer->phone_number }}</td>
                                            <td>{{ $customer->debtor_name }}</td>
                                            <td>{{ $customer->contract_number }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="empty">Belum ada customer. Silakan lakukan import data terlebih dahulu.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="submit-row">
                            <button type="submit" class="btn primary">Kirim Notifikasi ke Customer Terpilih</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        const selectAll = document.getElementById('selectAll');
        const checks = Array.from(document.querySelectorAll('.customer-check'));
        const selectedCount = document.getElementById('selectedCount');

        function updateCounter() {
            const total = checks.filter((item) => item.checked).length;
            selectedCount.textContent = total + ' dipilih';
            if (selectAll) {
                selectAll.checked = checks.length > 0 && total === checks.length;
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function () {
                checks.forEach((item) => { item.checked = this.checked; });
                updateCounter();
            });
        }

        checks.forEach((item) => item.addEventListener('change', updateCounter));
        updateCounter();
    </script>
</body>
</html>

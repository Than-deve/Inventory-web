@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header Halaman & Filter / Cetak PDF -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Kelola Peminjaman (Admin)</h2>
            <p class="text-sm text-gray-500 mt-1">Daftar riwayat dan pengelolaan status peminjaman barang.</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Filter Status Peminjaman -->
            <form method="GET" action="{{ route('admin.loans.index') }}" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="border-gray-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-2 bg-white shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Belum Disetujui</option>
                    <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </form>

            <!-- Tombol Cetak PDF -->
            <a href="{{ route('admin.loans.export_pdf', ['status' => request('status')]) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Cetak PDF
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Sukses -->
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
        <p class="font-medium text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Alert Notifikasi Error -->
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm">
        <p class="font-medium text-sm">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Tabel Data Peminjaman -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Batas Kembali</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($loans as $loan)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-semibold text-gray-900">{{ $loan->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $loan->user->email ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-medium text-gray-900">{{ $loan->item->name ?? '-' }}</p>
                            <p class="text-xs text-gray-500">Kode: {{ $loan->item->code ?? '-' }}</p>
                        </td>
                        <!-- Format Angka Ribuan -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-gray-700">
                            {{ number_format($loan->quantity, 0, ',', '.') }}
                        </td>
                        <!-- Format Tanggal Indonesia -->
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($loan->loan_date)->translatedFormat('d M Y') }}
                        </td>
                        <!-- Format Tanggal Indonesia -->
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($loan->status == 'proses')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                Belum Disetujui
                            </span>
                            @elseif($loan->status == 'dipinjam')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                Dipinjam
                            </span>
                            @elseif($loan->status == 'terlambat')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200 animate-pulse">
                                ⚠️ Terlambat
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Dikembalikan
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Persetujuan Peminjaman (Jika masih berstatus 'proses') --}}
                                @if($loan->status == 'proses')
                                <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm whitespace-nowrap">
                                        Setujui Pinjaman
                                    </button>
                                </form>
                                {{-- Tombol Proses Pengembalian (Untuk status dipinjam atau terlambat) --}}
                                @elseif($loan->status == 'dipinjam' || $loan->status == 'terlambat')
                                <form action="{{ route('admin.loans.return', $loan->id) }}" method="POST" class="flex items-center gap-1.5 inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="condition_on_return" required class="border-gray-300 rounded-lg text-xs focus:ring-blue-500 focus:border-blue-500 px-2 py-1 bg-white">
                                        <option value="baik">Baik</option>
                                        <option value="rusak ringan">Rusak Ringan</option>
                                        <option value="rusak berat">Rusak Berat</option>
                                    </select>
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold transition shadow-sm whitespace-nowrap">
                                        Proses Kembali
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-gray-400 italic">Selesai</span>
                                @endif

                                {{-- Tombol Hapus Data --}}
                                <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Hapus Data" class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-lg text-xs font-medium transition shadow-sm flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-400">
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
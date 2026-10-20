@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Header Halaman -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Peminjaman Saya</h2>
        <p class="text-sm text-gray-500 mt-1">Pantau status pengajuan peminjaman dan catatan kondisi barang Anda.</p>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl shadow-sm">
            <p class="font-medium text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Tabel Peminjaman Siswa -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Tenggat Kembali</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status Persetujuan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Kondisi Pengembalian</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-gray-50/50 transition">
                            <!-- Detail Barang -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-900">{{ $loan->item->name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">Kode: {{ $loan->item->code ?? '-' }}</p>
                            </td>

                            <!-- Jumlah Unit -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">
                                {{ number_format($loan->quantity, 0, ',', '.') }} Unit
                            </td>

                            <!-- Tanggal Pinjam (Format Indonesia) -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($loan->loan_date)->translatedFormat('d M Y') }}
                            </td>

                            <!-- Tenggat Kembali (Format Indonesia) -->
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d M Y') }}
                            </td>

                            <!-- Status Persetujuan Admin -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($loan->status == 'dipinjam')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                         Disetujui & Dipinjam
                                    </span>
                                @elseif($loan->status == 'terlambat')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        ⚠️ Terlambat Kembali
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                         Selesai / Dikembalikan
                                    </span>
                                @endif
                            </td>

                            <!-- Catatan Kondisi Saat Dikembalikan -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($loan->returnLog)
                                    @if($loan->returnLog->condition_on_return == 'baik')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                             Sangat Baik
                                        </span>
                                    @elseif($loan->returnLog->condition_on_return == 'rusak ringan')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                             Rusak Ringan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                             Rusak Berat
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum dikembalikan</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">
                                Anda belum pernah melakukan peminjaman barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
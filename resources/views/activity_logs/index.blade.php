@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6">Audit Log Aktivitas Sistem</h2>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3">Waktu</th>
                <th class="p-3">Pengguna</th>
                <th class="p-3">Aksi</th>
                <th class="p-3">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 text-sm text-gray-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                <td class="p-3 font-semibold">{{ $log->user->name }}</td>
                <td class="p-3"><code class="bg-gray-100 p-1 rounded text-xs">{{ $log->action }}</code></td>
                <td class="p-3">{{ $log->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
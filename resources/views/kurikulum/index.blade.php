
@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto p-6">
  <h1 class="text-2xl font-semibold mb-4">Kurikulum per Angkatan</h1>
  <form method="POST" action="{{ route('kurikulum.syncSiswa') }}" class="mb-4">
  @csrf
  <button class="px-3 py-2 bg-red-600 text-blue rounded">Sync Siswa dari Nomor Peserta</button>
</form>
  <div class="bg-white rounded shadow overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left p-3">Kode</th>
          <th class="text-left p-3">Nama</th>
          <th class="text-left p-3">Periode</th>
          <th class="text-left p-3">Siswa</th>
          <th class="text-left p-3">Mapel</th>
          <th class="text-left p-3">Total Jam </th>
          <th class="text-left p-3"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($angkatans as $a)
          <tr class="border-t">
            <td class="p-3">{{ $a->kode }}</td>
            <td class="p-3">{{ $a->nama }}</td>
            <td class="p-3">
  @if($a->mulai || $a->selesai)
    {{ optional($a->mulai)->format('d M Y') ?? '-' }} – {{ optional($a->selesai)->format('d M Y') ?? '-' }}
  @else
    <span class="text-gray-400">Belum diatur</span>
  @endif
            </td>
            <td class="p-3">{{ $a->siswa_count ?? 0 }}</td>
            <td class="p-3">{{ $a->mapel_count }}</td>
            <td class="p-3 font-semibold text-indigo-700">
                {{ $a->total_jam_pivot ?? 0 }} jam
            </td>
            <td class="p-3">
              <a class="text-blue-600 hover:underline" href="{{ route('kurikulum.show', $a) }}">Lihat</a>
            </td>
          </tr>
        @empty
          <tr><td class="p-3 text-gray-500" colspan="7">Belum ada angkatan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

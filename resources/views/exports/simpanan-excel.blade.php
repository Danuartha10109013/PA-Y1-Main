<table>
    <thead>
        <tr>
            <th>Nomor Invoice</th>
            <th>Nama</th>
            <th>Nominal</th>
            @if ($type === 'berjangka')
                <th>Jangka Waktu</th>
                <th>Jumlah Jasa</th>
            @endif
            <th>Status</th>
            <th>Virtual Account</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>
                    @if ($type == 'wajib') {{ $item->no_simpanan_wajib }}
                    @elseif ($type == 'pokok') {{ $item->no_simpanan_pokok }}
                    @else {{ $item->no_simpanan }} @endif
                </td>
                <td>
                    @php
                        $name = $item->anggota_id
                            ? \App\Models\Anggota::where('id', $item->anggota_id)->value('nama')
                            : \App\Models\User::where('id', $item->user_id)->value('name');
                    @endphp
                    {{ $name }}
                </td>
                <td>Rp. {{ number_format($item->nominal, 2) }}</td>
                @if ($type === 'berjangka')
                    <td>{{ $item->jangka_waktu }} Bulan</td>
                    <td>Rp. {{ number_format($item->jumlah_jasa_perbulan, 2) }}</td>
                @endif
                <td>
                    {{ ucfirst($type === 'pokok' || $type === 'wajib' ? $item->status_pembayaran : $item->status_payment) }}
                </td>
                <td>{{ $item->virtual_account ?? 'Tidak Ada' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f8f9fa;">
            <th style="border: 1px solid #dee2e6; padding: 8px;">Tarih</th>
            <th style="border: 1px solid #dee2e6; padding: 8px;">Misafir</th>
            <th style="border: 1px solid #dee2e6; padding: 8px;">Lokasyon</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $item->check_in_date->format('d.m.Y') }}</td>
                <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $item->resident->first_name }}
                    {{ $item->resident->last_name }}</td>
                <td style="border: 1px solid #dee2e6; padding: 8px;">{{ $item->location->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

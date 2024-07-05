<style>
    .wrapper {
        padding: 3rem;
    }
    * {
        font-family: sans-serif;
    }
    table {
        width: 100%;
        border-collapse: collapse
    }
    .logo {
        text-align: right;
    }
    .invoice-data {
        width: 300px;
    }
    th {
        border-bottom: 1px solid black;
    }
    .result {
        border-top: 1px solid black;
        font-weight: bold;
    }
</style>
<div class="wrapper">
<table>
    <tr>
        <td class="title">Rechnung</td>
        <td class="logo">LOGO</td>
    </tr>
</table>
<p>
    <br>
</p>
<table>
    <tr>
        <td class="customer">
            Name Name
            <br>
            Address
            <br>
            12345 City
        </td>
        <td class="invoice-data">
            Kundennummer: {{ $invoice->customer->number }}
            <br>
            Rechnungsnummer: {{ $invoice->number }}
            <br>
            Rechnungsdatum: {{ $invoice->created_at->format('d.m.Y') }}
            <br>
            Fälligkeitsdatum: {{ $invoice->deadline->format('d.m.Y') }}
        </td>
    </tr>
</table>
<p>
    Hallo {{ $invoice->customer->firstname }},
    <br>
    <br>
    vielen Dank für Ihre Bestellung. Wir hoffen, dass Sie viel Freude an der Ware haben und alles zu Ihrer Zufriedenheit ausgefallen ist.
    <br>
    <br>
    Herzliche Grüße
    Ihre ...
</p>
<table>
    <thead>
        <tr>
            <th>Produkt</th>
            <th>Einzelpreis</th>
            <th>Menge</th>
            <th>Rabatt</th>
            <th>Gesamtpreis</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->orderItems as $item)
        <tr>
            <td>
                @if (!empty($item->item->alternate_name))
                                {{ $item->item->alternate_name }}
                            @else
                                {{ $item->item->product->title }} {{ $item->item->additional_name }}
                            @endif
            </td>
            <td>{{ number_format($item->net_price, 2, ',', '.') }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->discount_sum, 2, ',', '.') }}</td>
            <td>{{ number_format($item->result_sum, 2, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4">Versandkosten</td>
            <td>{{ number_format($order->delivery_sum, 2, ',', '.') }}</td>
        </tr>
        <tr class="result">
            <td colspan="4">Insgesamt</td>
            <td>{{ number_format($order->result_sum, 2, ',', '.') }}</td>
        </tr>
    </tbody>
</table>
</div>

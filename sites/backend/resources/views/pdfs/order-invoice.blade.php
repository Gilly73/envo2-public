<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; }
    .header { text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 8px; }
  </style>
</head>
<body>
  <div class="header">
    <h1>Order #{{ $orderId }}</h1>
    <p>Thank you for your purchase, {{ $name }}!</p>
  </div>
  <table>
    <thead>
      <tr>
        <th>Couch</th>
        <th>Country</th>
        <th>Discount Code</th>
        <th>Discount</th>
        <th>Tax</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $items['title'] }}</td>
        <td>{{ $items['country'] }}</td>
        <td>{{ $items['discount_code'] }}</td>
        <td>{{ $items['discount'] }}</td>
        <td>{{ $items['tax'] }}</td>
      </tr>
    </tbody>
  </table>
  <p>Total Paid: £{{ number_format($items['total_cost'], 2) }}</p>
</body>
</html>

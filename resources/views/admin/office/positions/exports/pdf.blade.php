<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>گزارش بست‌های وظیفوی</title>
    <style>
        body { font-family: 'XB Niloofar', Tahoma; direction: rtl; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #4361ee; color: white; }
    </style>
</head>
<body>
<h1>گزارش بست‌های وظیفوی</h1>
<p>تاریخ تولید: {{ $date }}</p>

<table>
    <thead>
    <tr>
        <th>شناسه</th>
        <th>عنوان</th>
        <th>بست مافوق</th>
        <th>کدهای پر</th>
        <th>کدهای خالی</th>
        <th>درجه</th>
        <th>موقعیت</th>
    </tr>
    </thead>
    <tbody>
    @foreach($positions as $position)
        @php
            $filled = $position->codes->filter(fn($c) => $c->employee)->count();
            $empty = $position->codes->count() - $filled;
        @endphp
        <tr>
            <td>{{ $position->id }}</td>
            <td>{{ $position->title }}</td>
            <td>{{ $position->parent->title ?? 'ریاست' }}</td>
            <td>{{ $filled }}</td>
            <td>{{ $empty }}</td>
            <td>{{ $position->position_number }}</td>
            <td>{{ $position->place->name ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Office Stock Request</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="bg-slate-100 text-slate-900">
<main class="mx-auto max-w-2xl px-4 py-8 sm:py-14">
    <div class="mb-8"><p class="text-sm font-semibold uppercase tracking-[.2em] text-indigo-600">Internal Office Stock</p><h1 class="mt-2 text-3xl font-bold">Request supplies</h1><p class="mt-2 text-slate-600">Complete this form to send a request to the office team.</p></div>
    @if($errors->any())<div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="POST" action="{{ route('request.store') }}" class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200 sm:p-8">@csrf
        <div class="grid gap-5 sm:grid-cols-2"><div><label class="block text-sm font-medium">Your name</label><input name="employee_name" value="{{ old('employee_name') }}" required class="mt-1 w-full rounded-lg border-slate-300"> </div><div><label class="block text-sm font-medium">Phone</label><input name="employee_phone" value="{{ old('employee_phone') }}" required class="mt-1 w-full rounded-lg border-slate-300"></div></div>
        <div><label class="block text-sm font-medium">Department</label><input name="department" value="{{ old('department') }}" required class="mt-1 w-full rounded-lg border-slate-300"></div>
        <div class="grid gap-5 sm:grid-cols-2"><div><label class="block text-sm font-medium">Item</label><select name="item_id" required class="mt-1 w-full rounded-lg border-slate-300"><option value="">Choose an item</option>@foreach($items as $item)<option value="{{ $item->id }}" @selected(old('item_id') == $item->id)>{{ $item->name }} ({{ $item->unit }})</option>@endforeach</select></div><div><label class="block text-sm font-medium">Quantity</label><input type="number" min="1" name="quantity" value="{{ old('quantity', 1) }}" required class="mt-1 w-full rounded-lg border-slate-300"></div></div>
        <div><label class="block text-sm font-medium">Purpose</label><textarea name="purpose" rows="4" required class="mt-1 w-full rounded-lg border-slate-300">{{ old('purpose') }}</textarea></div>
        <div class="flex flex-wrap items-center justify-between gap-3"><a href="{{ route('request.status') }}" class="text-sm font-medium text-indigo-600">Check an existing request</a><button class="rounded-lg bg-indigo-600 px-5 py-3 font-semibold text-white hover:bg-indigo-700">Submit request</button></div>
    </form>
</main></body></html>

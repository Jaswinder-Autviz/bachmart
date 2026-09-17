<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['product', 'shop', 'customer'])->latest();

        if ($request->type) {
            $query->byType($request->type);
        }

        if ($request->period === 'today') {
            $query->today();
        } elseif ($request->period === 'week') {
            $query->thisWeek();
        } elseif ($request->period === 'month') {
            $query->thisMonth();
        }

        $leads = $query->paginate(30)->withQueryString();

        $summary = [
            'total' => Lead::count(),
            'call' => Lead::byType('call')->count(),
            'whatsapp' => Lead::byType('whatsapp')->count(),
            'direction' => Lead::byType('direction')->count(),
            'view' => Lead::byType('view')->count(),
            'today' => Lead::today()->count(),
        ];

        return view('admin.leads.index', compact('leads', 'summary'));
    }
}

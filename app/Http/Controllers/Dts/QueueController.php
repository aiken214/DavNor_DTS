<?php

namespace App\Http\Controllers\Dts;

use App\Http\Controllers\Controller;
use App\Models\QueueSetting;
use App\Models\QueueTicket;
use App\Models\DtsSystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class QueueController extends Controller
{
    // ─── Authenticated Admin Methods ────────────────────────────────

    public function index()
    {
        abort_if(Gate::denies('dts_queue_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $systemSetting = DtsSystemSetting::first();
        $queueName = QueueSetting::get('queue_name', 'Records Service Counter');
        $onBreak = QueueSetting::get('on_break', 'no') === 'yes';

        $totalWaitingRec = QueueTicket::today()->byType('receiving')->byStatus('waiting')->count();
        $totalWaitingRel = QueueTicket::today()->byType('releasing')->byStatus('waiting')->count();

        $recActiveNum = QueueTicket::today()->byType('receiving')->byStatus('calling')
            ->orderByDesc('id')->value('ticket_number');
        $relActiveNum = QueueTicket::today()->byType('releasing')->byStatus('calling')
            ->orderByDesc('id')->value('ticket_number');

        $currentRecDisplay = $recActiveNum ? 'REC-' . str_pad($recActiveNum, 3, '0', STR_PAD_LEFT) : '--';
        $currentRelDisplay = $relActiveNum ? 'REL-' . str_pad($relActiveNum, 3, '0', STR_PAD_LEFT) : '--';

        $recList = QueueTicket::today()->byType('receiving')->byStatus('waiting')
            ->orderBy('id')->limit(3)->pluck('ticket_number')->toArray();
        $relList = QueueTicket::today()->byType('releasing')->byStatus('waiting')
            ->orderBy('id')->limit(3)->pluck('ticket_number')->toArray();

        $recTrailHtml = !empty($recList)
            ? implode(', ', array_map(fn($n) => 'REC-' . str_pad($n, 3, '0', STR_PAD_LEFT), $recList))
            : 'None pending';
        $relTrailHtml = !empty($relList)
            ? implode(', ', array_map(fn($n) => 'REL-' . str_pad($n, 3, '0', STR_PAD_LEFT), $relList))
            : 'None pending';

        $clientUrl = url('/queue/client');

        return view('dts.queue-admin', compact(
            'systemSetting', 'queueName', 'onBreak',
            'totalWaitingRec', 'totalWaitingRel',
            'currentRecDisplay', 'currentRelDisplay',
            'recTrailHtml', 'relTrailHtml', 'clientUrl'
        ));
    }

    public function generateNewQueue(Request $request)
    {
        abort_if(Gate::denies('dts_queue_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate(['q_name' => 'required|string|max:100']);

        QueueSetting::set('queue_name', trim($request->input('q_name')));
        QueueTicket::truncate();

        return redirect()->route('dts.queue.index')->with('success', 'New queue generated successfully.');
    }

    public function callNext(Request $request)
    {
        abort_if(Gate::denies('dts_queue_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type = strtolower(trim($request->input('type', '')));
        if (!in_array($type, ['receiving', 'releasing'])) {
            return response()->json(['error' => 'Invalid type'], 422);
        }

        DB::transaction(function () use ($type) {
            QueueTicket::today()->byType($type)->byStatus('calling')
                ->update(['status' => 'completed']);

            $next = QueueTicket::today()->byType($type)->byStatus('waiting')
                ->orderBy('id')->first();

            if ($next) {
                $next->update(['status' => 'calling']);
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('dts.queue.index');
    }

    public function recall(Request $request)
    {
        abort_if(Gate::denies('dts_queue_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type = strtolower(trim($request->input('type', 'receiving')));

        $current = QueueTicket::today()->byType($type)->byStatus('calling')
            ->orderByDesc('id')->first();

        if ($current) {
            $current->touch();
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('dts.queue.index');
    }

    public function complete(Request $request)
    {
        abort_if(Gate::denies('dts_queue_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $type = strtolower(trim($request->input('type', 'receiving')));

        QueueTicket::today()->byType($type)->byStatus('calling')
            ->update(['status' => 'completed']);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('dts.queue.index');
    }

    public function toggleBreak()
    {
        abort_if(Gate::denies('dts_queue_manage'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $current = QueueSetting::get('on_break', 'no');
        QueueSetting::set('on_break', $current === 'yes' ? 'no' : 'yes');

        return redirect()->route('dts.queue.index');
    }

    // ─── Public API Methods ────────────────────────────────────────

    public function status()
    {
        $onBreak = QueueSetting::get('on_break', 'no') === 'yes';

        $recActive = QueueTicket::today()->byType('receiving')->byStatus('calling')
            ->orderByDesc('id')->value('ticket_number');
        $relActive = QueueTicket::today()->byType('releasing')->byStatus('calling')
            ->orderByDesc('id')->value('ticket_number');

        $recNext = QueueTicket::today()->byType('receiving')->byStatus('waiting')
            ->orderBy('id')->limit(3)->pluck('ticket_number')->toArray();
        $relNext = QueueTicket::today()->byType('releasing')->byStatus('waiting')
            ->orderBy('id')->limit(3)->pluck('ticket_number')->toArray();

        $recWaiting = QueueTicket::today()->byType('receiving')->byStatus('waiting')->count();
        $relWaiting = QueueTicket::today()->byType('releasing')->byStatus('waiting')->count();

        return response()->json([
            'onBreak' => $onBreak,
            'recActive' => $recActive,
            'relActive' => $relActive,
            'recNext' => $recNext,
            'relNext' => $relNext,
            'recWaiting' => $recWaiting,
            'relWaiting' => $relWaiting,
        ]);
    }

    public function clientPage()
    {
        return view('queue.client');
    }

    public function displayPage()
    {
        return view('queue.display');
    }

    public function joinQueue(Request $request)
    {
        $type = $request->input('type', 'receiving');
        if (!in_array($type, ['receiving', 'releasing'])) {
            return response()->json(['error' => 'Invalid type'], 422);
        }

        $nextNum = DB::transaction(function () use ($type) {
            $maxNum = QueueTicket::lockForUpdate()->today()->byType($type)->max('ticket_number');
            $nextNum = ($maxNum ?? 0) + 1;

            QueueTicket::create([
                'ticket_number' => $nextNum,
                'transaction_type' => $type,
                'client_name' => 'Visitor',
                'status' => 'waiting',
            ]);

            return $nextNum;
        });

        $ticket = QueueTicket::today()->byType($type)
            ->where('ticket_number', $nextNum)->first();

        return response()->json([
            'id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'type' => $type,
        ]);
    }

    public function ticketStatus(Request $request)
    {
        $id = $request->input('id', 0);
        $ticket = QueueTicket::find($id);

        if (!$ticket) {
            return response()->json(['error' => 'Not found']);
        }

        $aheadCount = QueueTicket::today()
            ->byType($ticket->transaction_type)
            ->byStatus('waiting')
            ->where('ticket_number', '<', $ticket->ticket_number)
            ->count();

        $servingRow = QueueTicket::today()->byStatus('calling')
            ->byType($ticket->transaction_type)
            ->orderByDesc('id')->first();

        $currentServing = 'None';
        if ($servingRow) {
            $prefix = $servingRow->transaction_type === 'receiving' ? 'REC-' : 'REL-';
            $currentServing = $prefix . str_pad($servingRow->ticket_number, 3, '0', STR_PAD_LEFT);
        }

        $onBreak = QueueSetting::get('on_break', 'no') === 'yes';

        return response()->json([
            'myTicket' => $ticket,
            'aheadCount' => $aheadCount,
            'currentServing' => $currentServing,
            'onBreak' => $onBreak,
        ]);
    }

    public function saveSubscription(Request $request)
    {
        $ticketId = $request->input('id', 0);
        $subscription = $request->input('subscription');

        if ($ticketId && $subscription) {
            QueueTicket::where('id', $ticketId)
                ->update(['push_subscription' => json_encode($subscription)]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'error' => 'Missing data']);
    }
}

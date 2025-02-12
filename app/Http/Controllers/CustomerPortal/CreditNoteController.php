<?php

namespace App\Http\Controllers\CustomerPortal;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CreditNoteController extends Controller
{
    /**
     * Display Customer Credit Notes Page
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currentCustomer = Customer::findByUid($request->customer);

        // Get Credit Notes by Customer
        $query = $currentCustomer->credit_notes()->nonDraft()->orderBy('credit_note_number')->getQuery();

        // Apply filters
        $credit_notes = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::partial('credit_note_number'),
                AllowedFilter::scope('from'),
                AllowedFilter::scope('to'),
            ])
            ->paginate()
            ->appends(request()->query());

        return view('customer_portal.credit_notes.index', [
            'credit_notes' => $credit_notes,
        ]);
    }

    /**
     * Display Credit Note Details Page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $credit_note = CreditNote::findByUid($request->credit_note);
        $customer = $credit_note->customer;

        // Check if it is already viewed or not
        $viewed = Activity::where('subject_id', $customer->id)->where('causer_id', $credit_note->id)->where('description', 'viewed')->first();
        if (!Auth::check() && !$viewed) {
            // Log credit note viewed
            activity()->on($customer)->by($credit_note)->log('viewed');
        }

        return view('customer_portal.credit_notes.details', [
            'credit_note' => $credit_note,
        ]);
    }
}

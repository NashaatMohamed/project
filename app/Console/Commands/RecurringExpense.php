<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Expense;
use Illuminate\Support\Facades\Log;

class RecurringExpense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:expenses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and create recurring expenses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get recurring Expense
        $expenses = Expense::recurring()->get();
        
        Log::info('Recurring expenses begin');
        Log::info($expenses);

        foreach ($expenses as $expense) {
            // Check date
            $now = Carbon::now()->format('Y-m-d');
            $next_recurring_at = Carbon::parse($expense->next_recurring_at)->format('Y-m-d');
            if ($now !== $next_recurring_at) {
                Log::info($expense->id .' Continue');
                continue;
            }

            Log::info($expense->id .' is recurring');
            
            // New cycle
            $newcycle = intval($expense->cycle);
            if (!in_array($newcycle, [-1, 0])) {
                $newcycle = $newcycle - 1;
            }

            // Save New Expense to Database
            $new_expense = Expense::create([
                'expense_category_id' => $expense->expense_category_id,
                'amount' => $expense->amount,
                'company_id' => $expense->company_id,
                'vendor_id' => $expense->vendor_id,
                'expense_date' => Carbon::now(),
                'notes' => $expense->notes,
                'is_recurring' => $expense->is_recurring,
                'cycle' => $newcycle,
                'parent_expense_id' => $expense->parent_expense_id || $expense->id
            ]);

            // Set next recurring date
            if ($newcycle != 0) {
                $new_expense->next_recurring_at = Carbon::parse($new_expense->expense_date)->addMonths(intval($expense->is_recurring))->format('Y-m-d');
                $new_expense->save();
            }

            // Remove recurring from old expense
            $expense->is_recurring = 0;
            $expense->cycle = 0;
            $expense->save();

            // Add custom field values
            $custom_fields = $expense->fields;
            foreach ($custom_fields as $field) {
                $new_expense->fields()->create([
                    "custom_field_id" => $field->custom_field_id,
                    "company_id" => $field->company_id,
                    "type" => $field->company_id,
                    "string_answer" => $field->string_answer,
                    "number_answer" => intval($field->number_answer),
                    "boolean_answer" => boolval($field->boolean_answer),
                    "date_time_answer" => $field->date_time_answer,
                    "date_answer" => $field->date_answer,
                    "time_answer" => $field->time_answer,
                ]);
            }
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\alert;

class InvoiceController extends Controller
{
    public function get_all_invoice() {
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get();

        return  response()->json([
            'invoices' => $invoices
        ], 200);
    }

    public function search_invoice(Request $request) {
        $search = $request->get('s');
        if($search != null){
            $invoices = Invoice::with('customer')->where('date', 'LIKE',"%$search%")->get();
            return response()->json(
                [
                    'invoices' => $invoices
                ],200
            );
        }else{
            return $this->get_all_invoice();
        }

    }

    public function create_invoice(Request $request){
        
        $counter = Counter::where('key', 'invoice')->first();
        $random = Counter::where('key', 'invoice')->first();
        $invoice = Counter::orderBy('id', 'DESC')->first();

        if($invoice){
            $invoice = $invoice->id+1;
            $counters = $counter->value + $invoice;
        }else{
            $counters = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counters,
            'customer_id' => null,
            'customer' => null,
            'date' => date('y-m-d'),
            'due_date' => null,
            'discount' => 0,
            'term_and_conditons' => 'default terms and conditions',
            'items' => [
                'product_id' => null,
                'product' => null,
                'unit_price' => 0,
                'quantity' => 1
            ]
        ];
        return response()->json(
            $formData
        );
    }

    public function add_invoice(Request $request) {

        $validator =Validator::make(
            $request->all(),[
                'date' => 'required',
                'number' => 'required'
            ]
        ) ;

        if($validator->fails()) {
            return  response()->json(['error' => $validator->errors()], 404);
        }

        $invoiceitem = $request->input("invoice_item");

        $invoicedata['sub_total'] = $request->input('subtotal');
        $invoicedata['total'] = $request->input('total');
        $invoicedata['customer_id'] = $request->input('customer_id');
        $invoicedata['number'] = $request->input('number');
        $invoicedata['date'] = $request->input('date');
        $invoicedata['due_date'] = $request->input('due_date');
        $invoicedata['discount'] = $request->input('discount');
        $invoicedata['reference'] = $request->input('reference');
        $invoicedata['terms_and_conditions'] = $request->input('terms_and_conditions');


        $invoice = Invoice::create($invoicedata);

        foreach(json_decode($invoiceitem) as $item) {
            $itemdata['product_id'] = $item->id;
            $itemdata['invoice_id'] = $invoice->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;


            InvoiceItem::create($itemdata);
        }
        
    }

    public function getDetailinvoice($id) {
        $invioce = Invoice::with(['customer','invoice_items.product'])->find($id);
        return response()->json([
            'invoice' => $invioce
        ],200);
    }

    public function editInvoice($id) {
        $invioce = Invoice::with(['customer','invoice_items.product'])->find($id);
        return response()->json([
            'invoice' => $invioce
        ],200);
    }
    public function updateInvoiceItems(Request $request, $id) {

        $invoice = Invoice::where('id', $id)->first();

        $invoice->sub_total = $request->subtotal;
        $invoice->total = $request->total;
        $invoice->customer_id = $request->customer_id;
        $invoice->number = $request->number;
        $invoice->date = $request->date;
        $invoice->due_date = $request->due_date;
        $invoice->discount = $request->discount;
        $invoice->reference = $request->reference;
        $invoice->terms_and_conditions = $request->terms_and_conditions;

        $invoice->update($request->all());

        $invoice_item = $request->input("invoice_item");

        $invoice->invoice_items()->delete();

        foreach(json_decode($invoice_item) as $item) {
            $itemdata['product_id'] = $item->product_id;
            $itemdata['invoice_id'] = $invoice->id;
            $itemdata['quantity'] = $item->quantity;
            $itemdata['unit_price'] = $item->unit_price;


            InvoiceItem::create($itemdata);
        }
        
    }
    public function deteleInvoiceItems($id) {
        $invioceitem = InvoiceItem::findOrFail($id);
        $invioceitem->delete();
    }

    public function deleteInvoice($id) {
        $invioce = Invoice::findOrFail($id);
        $invioce->invoice_items()->delete();
        $invioce->delete();
    }
}

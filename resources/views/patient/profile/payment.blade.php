
<table class="table">
    <thead>
        <tr>
            <th>Bill No</th>
            <th>Payment ID</th>
            <th>Amount</th>
            <th>Method of Payment</th>
            <th>Insurance Provider</th>
            <th>Reference Number</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Payment Date</th>
            <th>Paid By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->patientVisits as $visit)
            @foreach($visit->bills as $bill)
                @foreach($bill->payments as $payment)
                <tr>
                    <td>{{$bill->bill_number}}</td>
                    <td>{{$payment->payment_id}}</td>
                    <td>{{$payment->amount}}</td>
                    <td>{{$payment->payment_method}}</td>
                    <td>{{$payment->insurance_provider}}</td>
                    <td>{{$payment->reference_number}}</td>
                    <td>{{$payment->status}}</td>
                    <td>{{$payment->notes}}</td>
                    <td>{{$payment->payment_date}}</td>
                    <td>{{$payment->recordedBy->name}}</td>
                </tr>
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>
<div class="infor">
    <p>Total:  {{$patient->payment()['total']}}</p>
    <p>Count:  {{$patient->payment()['count']}}</p>
    <p>Paid:  {{$patient->payment()['paid']}}</p>
    <p>Pending:  {{$patient->payment()['pending']}}</p>
    <p>Reversed:  {{$patient->payment()['reversed']}}</p>
</div>
 
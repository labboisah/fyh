
<table class="table">
    <thead>
        <tr>
            <th>Bill No</th>
            <th>Service Description</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Issued Date</th>
            <th>Due Date</th>
            <th>Issued By</th>
        </tr>
    </thead>
    <tbody>
        @foreach($patient->patientVisits as $visit)
            @foreach($visit->bills as $bill)
            <tr>
                <td>{{$bill->bill_number}}</td>
                <td>{{$bill->service_description}}</td>
                <td>{{ number_format($bill->due_amount, 2) }}</td>
                <td>{{$bill->status}}</td>
                <td>{{$bill->notes}}</td>
                <td>{{$bill->issued_date}}</td>
                <td>{{$bill->due_date}}</td>
                <td>{{$bill->issuedBy->name}}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
<div class="infor">
    <p>Total:  {{$patient->bills()['amount']}}</p>
    <p>Paid:  {{$patient->bills()['paid']}}</p>
    <p>Pending:  {{$patient->bills()['pending']}}</p>
    <p>Overdue:  {{$patient->bills()['overdue']}}</p>
</div>
 
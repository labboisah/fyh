@foreach($patient->currentVisit()->admissions->where('status', 'confirmed') as $admission)
    @foreach($admission->fluidBalances as $fluid)
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-12">
                <p>Recording Date: {{$fluid->date}}</p>
                <p>Recording Time: {{$fluid->time}}</p>
                <p>Recorded By: {{$fluid->recordedBy->name}}</p>
            </div>
            <div class="col-md-6">
                <h5 class="text-muted">INPUT (ML)</h5>
                <p>Type in: {{$fluid->type_in}}</p>
                <p>Tube in: {{$fluid->tube_in}}</p>
                <p>Oral in: {{$fluid->oral}}</p>
                <p>IV: {{$fluid->iv}}</p>
            </div>
            <div class="col-md-6">
                <h5 class="text-muted">INPUT (ML)</h5>
                <p>Type out: {{$fluid->type_out}}</p>
                <p>Tube out: {{$fluid->tube_out}}</p>
                <p>Urine: {{$fluid->urine}}</p>
                <p>Faces: {{$fluid->faces}}</p>
               
            </div>
            <div class="col-md-4 offset-4">
                <p>Total In: {{$fluid->totalIn()}}</p>
                <p>Total Out: {{$fluid->totalOut()}}</p>
                <p>Balance: {{$fluid->balance()}}</p>
            </div>
        </div>
    </div>
        
    @endforeach
@endforeach
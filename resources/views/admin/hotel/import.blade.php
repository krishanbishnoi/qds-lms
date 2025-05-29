@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header heading-title">
        Import Hotels
    </div>
    <div class="card-body">
        <form action="{{ route("admin.importCSVFile") }}" enctype="multipart/form-data" method="POST" class="form-padding">
            @csrf
            <div class="form-group">
                <div class="grp-f">
                    <label for="description">Select CSV*</label>
                    <input type="file" accept=".csv" class="form-control" class="form-control" name="csvFile" />
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Import</button>
            </div>
        </form>
    </div>
</div>
@if($rowEror)
<h5 style="color: green"><strong>Hotel Imported Successfully</strong></h5>
<div class="importWarnings mb-10 mt-3 p-10">
    <div>
        <h5>Warnings</h5>
        <div class="customTableOuter">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Row No.</th>
                        <th scope="col">Warnings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rowEror as $key => $rowEr )
                    <tr>
                        <th scope="row">{{$key}}</th>
                        <td>
                            <ol>
                                @foreach ($rowEr['inValidCommonAmenitiesArr'] as $CArowEr)
                                <li>{{$CArowEr}} not available in common amenities.</li>
                                @endforeach
                                @foreach ($rowEr['inValidRoomAmenitiesArr'] as $RArowEr)
                                <li>{{$RArowEr}} not available in room amenities.</li>
                                @endforeach
                                @foreach ($rowEr['inValidBedtypeArr'] as $BTrowEr)
                                <li>{{$BTrowEr}} not available in bet type.</li>
                                @endforeach
                            </ol>
                        </td>
                    </tr>
                    @endforeach
            </table>
            </div>
    </div>
</div>
@endif
@endsection

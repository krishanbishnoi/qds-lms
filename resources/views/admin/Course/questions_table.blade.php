<table class="table table-hover table table-bordered mt-2">
    <thead class="theadLight">
        <tr>
            <th>S.No.</th>
            <th>Question</th>
            <th>Marks</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($questions as $result)
            <tr class="items-inner">
                <td>{{ $loop->iteration . '.' }}</td>
                <td>{{ $result->question }}</td>
                <td>{{ $result->marks }}</td>
                <td>
                    <a href='{{ route("$modelName.delete", "$result->id") }}' data-delete="delete"
                        class="delete_any_item btn btn-danger" title="Delete">
                        <span class="fas fa-trash-alt"></span>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

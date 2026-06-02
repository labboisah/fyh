<a href="{{ route('accountant.bills.show', $bill) }}" class="btn btn-sm btn-info" title="View">
    <i class="bi bi-eye"></i>
</a>
<a href="{{ route('accountant.bills.edit', $bill) }}" class="btn btn-sm btn-warning" title="Edit">
    <i class="bi bi-pencil"></i>
</a>
<form action="{{ route('accountant.bills.delete', $bill) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
        <i class="bi bi-trash"></i>
    </button>
</form>

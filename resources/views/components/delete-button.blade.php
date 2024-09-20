<form action="{{ $route }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure want to delete?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
        <i class="fa fa-trash" aria-hidden="true"></i>
    </button>
</form>
@props(['edit', 'view', 'delete'])
<div class="button-group">
    @if (isset($edit))
        <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal"
            data-target="#{{ $edit }}">
            <ion-icon name="pencil"></ion-icon>
        </button>
    @endif
    @if (isset($view))
        <button type="button" class="btn bg-gradient-info btn-sm" data-toggle="modal" data-target="#{{ $view }}">
            <ion-icon name="eye"></ion-icon>
        </button>
    @endif
    <a href="{{ $delete }}" role="button" class="btn bg-gradient-danger btn-sm">
        <ion-icon name="trash"></ion-icon>
    </a>
</div>

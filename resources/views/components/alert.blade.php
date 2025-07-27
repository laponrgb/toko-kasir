@props(['type'])

<div class="alert alert-dismissible fade show alert-{{ $type }}" role="alert">
    {{ $slot }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

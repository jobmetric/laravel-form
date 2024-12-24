<li class="nav-item" role="presentation">
    <a class="nav-link text-active-primary pb-4 @if($field->selected) active @endif" data-bs-toggle="tab" href="#{{ $field->id }}" aria-selected="true" role="tab">{{ trans($field->label) }}</a>
</li>

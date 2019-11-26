@foreach (['info', 'success', 'warning', 'danger'] as $msg)
    @if(session()->has('alert-' . $msg))
        <div class="animated shake alert alert-{{ $msg }}">
            <button type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-hidden="true"
            >&times;
            </button>

            {!! session()->get('alert-' . $msg) !!}
        </div>

        {{ session()->forget('alert-' . $msg) }}
    @endif
@endforeach


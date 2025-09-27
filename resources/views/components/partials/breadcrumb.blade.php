@php
    use Illuminate\Support\Str;

    $segments = request()->segments();
    $url = '';
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            {{-- Judul ambil dari segmen terakhir atau variabel $title --}}
            <h4 class="mb-sm-0">{{ $title ?? Str::title(str_replace('-', ' ', last($segments))) }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    {{-- Default dashboard --}}
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Dashboard</a>
                    </li>

                    {{-- Generate breadcrumb dari segmen --}}
                    @foreach ($segments as $key => $segment)
                        @php
                            $url .= '/' . $segment;
                        @endphp

                        @if ($loop->last)
                            <li class="breadcrumb-item active">
                                {{ Str::title(str_replace('-', ' ', $segment)) }}
                            </li>
                        @else
                            <li class="breadcrumb-item">
                                <a href="{{ url($url) }}">
                                    {{ Str::title(str_replace('-', ' ', $segment)) }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</div>

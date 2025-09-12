<ul>
    @foreach ($niveau_child as $niveau)
        <li>
            <a href="{{ $niveau->url }}">{{ $niveau->libelle }}</a>
          <span >
            <a href="{{ route('niveau.edit', $niveau['id']) }}" class="fs-5" style="margin-left:30px"> <i
                class=" ri ri-edit-2-fill ml-4"></i></a>

        <a href="{{ route('niveau.add-subCat', $niveau['id']) }}" class="fs-5"> <i
                class=" ri ri-add-circle-fill ml-4"></i>
        </a>
        @if (count($niveau->children) == 0)
            <a href="#" class="fs-5 delete" data-id="{{ $niveau['id'] }}"> <i
                    class="ri ri-delete-bin-2-line text-danger"></i>
            </a>
        @endif
          </span>
            @if ($niveau->children->count() > 0)
                @include('backend.pages.cycle_niveau.partials.subcategorie', [
                    'niveau_child' => $niveau->children,
                ])
            @endif
        </li>
    @endforeach
</ul>




@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            var route = "niveau"
            delete_row(route);
        });
    </script>
@endsection

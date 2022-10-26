
@foreach ($itemsCategory as $item)
    @if($item['display'] == 'grid')
        @include('news.pages.home.child-index.category_grid')
    @elseif($item['display'] == 'list')
        @include('news.pages.home.child-index.category_list')
    @endif
@endforeach

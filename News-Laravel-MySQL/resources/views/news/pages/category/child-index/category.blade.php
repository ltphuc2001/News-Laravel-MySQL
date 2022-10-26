@if($item['display'] == 'grid')
    @include('news.pages.category.child-index.category_grid')
@elseif($item['display'] == 'list')
    @include('news.pages.category.child-index.category_list')
@endif

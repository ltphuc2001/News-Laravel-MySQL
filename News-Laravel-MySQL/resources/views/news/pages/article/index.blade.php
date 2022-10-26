@extends('news.main')
@section('content')

<!-- Header -->

<!-- Home -->

<!-- Content Container -->
<div class="section-category">
    @include('news.block.breadcrumb_article', ['item' => $itemArticle])
    <div class="content_container container_category">
        <div class="featured_title">
            <div class="container">
                <div class="row">
                    <!-- Main Content -->
                    <div class="col-lg-9">
                        <div class = "single-post">
                            @include('news.pages.article.child-index.article', ['item' => $itemArticle])
                            @include('news.pages.article.child-index.related', ['item' => $itemArticle])
                        </div>

                    </div>
                    <!-- Sidebar -->
                    <div class="col-lg-3">
                        <div class="sidebar">
                            <!-- Latest Posts -->
                            @include('news.block.latest', ['items' => $itemsLatest])
                            <!-- Advertisement -->
                            <!-- Extra -->
                            @include('news.block.advertisement', ['itemsAdvertisement' => []])
                            <!-- Most Viewed -->
                            @include('news.block.most_viewed', ['itemsMostViewed' => []])
                            <!-- Tags -->
                            @include('news.block.tags', ['itemsTags' => []])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

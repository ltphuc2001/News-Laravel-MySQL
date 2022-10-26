<div class="posts">
    @foreach($item['article']  as $article)
        <div class="post_item post_h_large">
            <div class="row">
                <div class="col-lg-5">
                    @include('news.partials.article.image', ['item' => $article,'lengthContent' => 0 ])
                </div>
                <div class="col-lg-7">
                    @include('news.partials.article.content', ['item' => $article, 'lengthContent' => 200, 'showCategory' => false ])
                </div>
            </div>
        </div>
    @endforeach
</div>

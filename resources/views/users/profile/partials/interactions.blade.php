@foreach($interactionsPages as $interaction)
    <div class="col-lg-6 followed-page">
        <div class="card mb-3">
            <div class="row no-gutters position-relative">
                @if(isset($interaction['picture']))
                    <div class="col-md-4 col-lg-5">
                        <img src="{{ $interaction['picture'] }}" class="followed-image card-img">
                    </div>
                @endif
                <div class="col-md-8 col-lg-7 position-static">
                    <div class="card-body pb-1">
                        <h4 class="card-title mb-0">
                            <a class="stretched-link" style="color: inherit; text-decoration: none;" target="_blank" href="{{config('neayi.wiki_url').'/index.php?curid='.$interaction['page_id']}}">
                                {{ $interaction['title'] }}
                            </a>
                        </h4>
                        <div class="card-text applause-count d-inline-block">
                            {{ $interaction['applause']}}
                            <img src="{{ asset('images/applause.png') }}">
                        </div>
                        <span class="badge badge-grey">
                            {{$interaction['type']}}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

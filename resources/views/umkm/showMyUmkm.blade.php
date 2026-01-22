@extends('layouts.app')
@section('back')
    <a href="{{ url('/my-umkm') }}" class="back-btn"> <i class="icon-left"></i> </a>
@endsection
@section('container')
    <div id="app-wrap">
        <div class="bill-content">
            <div class="f-carousel" id="myCarousel">
                @foreach ($umkm->items as $key => $item)
                    <div
                        class="f-carousel__slide"
                        data-fancybox="gallery"
                        data-src="{{ url('/storage/'.$item->umkm_file_path) }}"
                        data-thumb-src="{{ url('/storage/'.$item->umkm_file_path) }}"
                    >
                        <img
                            data-lazy-src="{{ url('/storage/'.$item->umkm_file_path) }}"
                            alt="Image #{{ $key + 1 }}"
                        />
                    </div>
                @endforeach
                @if ($umkm->video_file_path)
                    <div
                        class="f-carousel__slide"
                        data-fancybox="gallery"
                        data-src="{{ url('/storage/'.$umkm->video_file_path) }}"
                        data-thumb-src="{{ url('/storage/'.$umkm->items->first()->umkm_file_path) }}"
                    >
                        <img
                            data-lazy-src="{{ url('/storage/'.$umkm->items->first()->umkm_file_path) }}"
                            alt="Image #{{ $key + 1 }}"
                        />
                    </div>
                @endif
            </div>

            <div class="app-section bg_white_color giftcard-detail-section-1">
                <div class="tf-container">
                    <div class="voucher-info">
                        <h2 class="fw_6">{{ $umkm->name ?? '-' }}</h2>
                        <a href="#" class="critical_color fw_6">Rp {{ number_format($umkm->price) }}</a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center top mt-2">
                        <h4 class="fw_6">Telah dilihat</h4>
                        <h4 class="fw_6">{{ $umkm->click }} x</h4>
                    </div>
                    <hr style="color: rgb(180, 180, 180)">
                    <div class="voucher-desc">
                        <h4 class="fw_6">Deskripsi</h4>
                        <p class="mt-1">{!! $umkm->description ? nl2br(e($umkm->description)) : '-' !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-navigation-bar st2 bottom-btn-fixed" style="bottom:65px">
        <div class="tf-container">
            <div class="row">
                <div class="col">
                    <a class="tf-btn small" style="color: green; border:1px solid green; background-color:white;" href="{{ url('/my-umkm/edit/'.$umkm->id) }}"><i class="fas fa-pencil-alt"></i>Edit</a>
                </div>
                <div class="col">
                    <a id="btn-logout" href="#" class="tf-btn small" style="color: red; border:1px solid red; background-color:white;"><i class="fas fa-trash"></i>Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <div class="tf-panel logout">
        <div class="panel_overlay"></div>
        <div class="panel-box panel-center panel-logout">
                <div class="heading">
                    <h2 class="text-center">Anda yakin ingin menghapus data ini?</h2>
                </div>
                <div class="bottom">
                    <a class="clear-panel" href="#">Cancel</a>
                    <a class="clear-panel critical_color" href="{{ url('/my-umkm/delete/'.$umkm->id) }}">Delete</a>
                </div>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    @push('style')
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.css"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.lazyload.css"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.arrows.css"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.thumbs.css"
        />
        <style>
            #myCarousel {
                --f-arrow-pos: 10px;
                --f-arrow-bg: rgba(255,255,255,0.75);
                --f-arrow-hover-bg: rgba(255,255,255,1);
                --f-arrow-color: #333;
                --f-arrow-width: 40px;
                --f-arrow-height: 40px;
                --f-arrow-svg-width: 20px;
                --f-arrow-svg-height: 20px;
                --f-arrow-svg-stroke-width: 2px;
                --f-arrow-border-radius: 50%;

                height: 400px;
            }

            #myCarousel .f-carousel__slide {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            #myCarousel img {
                max-width: 100%;
                max-height: 100%;
                height: auto;
            }
        </style>
    @endpush

    @push('script')
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.lazyload.umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.arrows.umd.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/carousel/carousel.thumbs.umd.js"></script>
        <script>
            Carousel(document.getElementById("myCarousel"), {
            }, {
                Lazyload,
                Arrows,
                Thumbs
            }).init();

            Fancybox.bind("[data-fancybox]", {
            });
        </script>
    @endpush
@endsection

@extends('layouts.app')
@section('back')
    @if (auth()->user())
        <a href="{{ url('/my-berita') }}" class="back-btn"> <i class="icon-left"></i> </a>
    @endif
@endsection
@section('container')
    <div id="app-wrap">
        <div class="bill-content">
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ url('/storage/'.$berita->berita_file_path) }}" class="d-block w-100" alt="...">
                    </div>
                </div>
            </div>

            <div class="tf-container mt-4">
                <div class="voucher-info">
                    <h2 class="fw_6">{{ $berita->judul ?? '-' }}</h2>
                </div>
            </div>

            <div class="app-section mt-1 bg_white_color giftcard-detail-section-2">
                <div class="tf-container">
                    <div class="voucher-desc">
                        <p class="mt-1">{!! $berita->isi ? nl2br(e($berita->isi)) : '-' !!}</p>
                    </div>
                    <hr style="color: rgb(180, 180, 180)">
                    <div class="voucher-desc">
                        <h4 class="fw_6">Komentar</h4>
                        @if (count($comments) == 0)
                            <center>
                                <hr>
                                    Tidak ada data
                                <hr>
                            </center>
                        @else
                            <ul class="mt-4">
                                @foreach ($comments as $comment)
                                    <li class="list-card-invoice tf-topbar d-flex justify-content-between align-items-center">
                                        <div class="user-info">
                                            @if ($comment->user && $comment->user->foto)
                                                <img src="{{ url('/storage/'.$comment->user->foto) }}">
                                            @else
                                                <img src="{{ url('assets/img/foto_default.jpg') }}">
                                            @endif
                                        </div>
                                        <div class="content-right">
                                            <h4>
                                                <a href="#" style="font-weight: bold;">
                                                    {{ $comment->user->name ?? '-' }}
                                                </a>
                                                <a style="font-size: 10px" href="#">
                                                    {!! $comment->comment ? nl2br(e($comment->comment)) : '-' !!}
                                                </a>
                                            </h4>
                                        </div>
                                        @if ($comment->user_id == auth()->user()->id)
                                            <div style="float: right;">
                                                <form action="{{ url('/comment/delete/'.$comment->id) }}" method="post">
                                                    @method('delete')
                                                    @csrf
                                                    <button class="border-0" title="Delete Comment" style="color: red;" onClick="return confirm('Are You Sure')"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <form action="{{ url('/my-berita/comment/'.$berita->id) }}" method="POST" class="tf-form mt-4">
                            @csrf
                            <div class="group-input">
                                <label for="comment">Komentar</label>
                                <textarea name="comment" id="comment" class="@error('comment') is-invalid @enderror" cols="30" rows="8">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="tf-btn accent large">Save</button>
                        </form>
                    </div>
                </div>
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

    @push('script')
        <script>
            $(document).ready(function() {
                $(document).on('click', '.btn-logout', function(e) {
                    e.preventDefault();
                    const targetModal = $(this).data('target');
                    $(targetModal).addClass("panel-open");
                });

                $(document).on('click', '.panel_overlay, .clear-panel', function(e) {
                    e.preventDefault();
                    $(".logout").removeClass("panel-open");
                });

                $(".clickable").on("click", function() {
                    window.location.href = $(this).data("url");
                });
            });
        </script>
    @endpush


@endsection

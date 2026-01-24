@extends('layouts.app')
@section('back')
    <a href="{{ url('/my-gate-card') }}" class="back-btn"> <i class="icon-left"></i> </a>
@endsection
@section('container')
    <form class="tf-form" action="{{ url('/my-gate-card/store') }}" enctype="multipart/form-data" method="POST">
        <div id="app-wrap" class="mt-4">
            <div class="bill-content">
                <div class="tf-container ms-4 me-4">
                    <div class="card-secton transfer-section mt-2">
                        <div class="tf-container">
                            @csrf

                            <div class="group-input">
                                <label for="name">Nama</label>
                                <input type="text" class="@error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" readonly>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="@error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', auth()->user()->alamat) }}" readonly>
                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="status_rumah">Status Rumah</label>
                                <input type="text" class="@error('status_rumah') is-invalid @enderror" id="status_rumah" name="status_rumah" value="{{ old('status_rumah', auth()->user()->status) }}" readonly>
                                @error('status_rumah')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="date">Tanggal</label>
                                <input type="text" class="@error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" readonly>
                                @error('date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="type">Jenis Transaksi</label>
                                <input type="text" class="@error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type', 'Gate Card') }}" readonly>
                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="card" style="border-radius: 10px; border: 1px solid #acacac;">
                                <div class="card-body">
                                    <label>Status Gate Card</label>
                                    <div class="form-check">
                                        <input class="form-check-input status_gate_card @error('status_gate_card') is-invalid @enderror" type="radio" id="radio1" name="status_gate_card" value="Kartu Akses Baru" {{ old('status_gate_card') == 'Kartu Akses Baru' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radio1">
                                            Kartu Akses Baru
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status_gate_card @error('status_gate_card') is-invalid @enderror" type="radio" id="radio2" name="status_gate_card" value="Kartu Akses Tambahan" {{ old('status_gate_card') == 'Kartu Akses Tambahan' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radio2">
                                            Kartu Akses Tambahan
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input status_gate_card @error('status_gate_card') is-invalid @enderror" type="radio" id="radio3" name="status_gate_card" value="Katu Akses Pengganti" {{ old('status_gate_card') == 'Katu Akses Pengganti' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radio3">
                                            Katu Akses Pengganti
                                        </label>
                                        <div class="textContainer">
                                            <input type="text" class="@error('status_gate_card_text') is-invalid @enderror borderi status_gate_card_text" name="status_gate_card_text" id="status_gate_card_text" value="{{ old('status_gate_card_text') }}" placeholder="Alasan">
                                            @error('status_gate_card_text')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        @error('status_gate_card')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <br>
                            
                            <div class="group-input">
                                <label for="payment_source" style="z-index: 1000">Jenis Pembayaran</label>
                                <select name="payment_source" id="payment_source" class="@error('payment_source') is-invalid @enderror select2" data-live-search="true">
                                    <option value="">-- Pilih Jenis Pembayaran --</option>
                                    <option value="Bank Transfer (Perlu Konfirmasi Pembayaran Manual)" {{ 'Bank Transfer (Perlu Konfirmasi Pembayaran Manual)' == old('payment_source') ? 'selected="selected"' : '' }}>Bank Transfer (Perlu Konfirmasi Pembayaran Manual)</option>
                                </select>
                                @error('type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="nominal">Nominal Pembayaran</label>
                                <input type="text" class="money @error('nominal') is-invalid @enderror" id="nominal" name="nominal" value="{{ old('nominal') }}">
                                @error('nominal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="qty">Jumlah Gate Card Yang Dipesan</label>
                                <input type="number" class="@error('qty') is-invalid @enderror" id="qty" name="qty" value="{{ old('qty') }}">
                                @error('qty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="card" style="border-radius: 10px; border: 1px solid #acacac;">
                                <div class="card-body">
                                    <label>Jenis Kendaraan</label>
                                    <div class="form-check">
                                        <input class="form-check-input vehicle_type @error('vehicle_type') is-invalid @enderror" type="radio" id="radio4" name="vehicle_type" value="Mobil" {{ old('vehicle_type') == 'Mobil' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radio4">
                                            Mobil
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input vehicle_type @error('vehicle_type') is-invalid @enderror" type="radio" id="radio5" name="vehicle_type" value="Motor" {{ old('vehicle_type') == 'Motor' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radio5">
                                            Motor
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input vehicle_type @error('vehicle_type') is-invalid @enderror" type="radio" id="radio6" name="vehicle_type" value="Lainnya" {{ old('vehicle_type') == 'Lainnya' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="radio6">
                                            Lainnya
                                        </label>
                                        <div class="textContainer2">
                                            <input type="text" class="@error('vehicle_type_text') is-invalid @enderror borderi vehicle_type_text" name="vehicle_type_text" id="vehicle_type_text" value="{{ old('vehicle_type_text') }}">
                                            @error('vehicle_type_text')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        @error('vehicle_type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <br>

                            <div class="group-input">
                                <label for="notes">Nomor Polisi Kendaraan</label>
                                <textarea name="notes" id="notes" class="@error('notes') is-invalid @enderror" cols="30" rows="5"> {{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div id="fileContainer" style="margin-top: -15px">
                                Bukti Pembayaran (Jika Sudah Transfer)
                                <div class="group-input">
                                    <input class="form-control @error('file_transaction_path') is-invalid @enderror" type="file" id="file_transaction_path" name="file_transaction_path">
                                    @error('file_transaction_path')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="alert" role="alert" style="color: white; background-color: black;">
                                    Silahkan transfer ke rekening ini : <span class="me-1" style="font-weight: bold;">Bank Syariah Indonesia (BSI)</span> Nama Penerima : <span class="me-1" style="font-weight: bold;">RT 001 RW 016 CLUSTER MADRID MGC</span> No. Rekening : <a id="copy"><span style="font-weight: bold;">8880010167</span> <i class="fas fa-copy ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-navigation-bar st2 bottom-btn-fixed" style="bottom:65px">
            <div class="tf-container">
                <button type="submit" class="tf-btn accent large">Save</button>
            </div>
        </div>

    </form>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script>
            $('.money').mask('000,000,000,000,000', {
                reverse: true
            });

            $('.select2').select2();

            $('#copy').on('click', function(e) {
                e.preventDefault();
                var urlToCopy = "8880010167";
                var tempInput = $('<input>');
                $('body').append(tempInput);
                tempInput.val(urlToCopy).select();
                document.execCommand('copy');
                tempInput.remove();
                Swal.fire({
                    title: "Nomor Rekening Berhasil Dicopy",
                    icon: "success",
                    timer: 1500
                });
            });

            let status_gate_card = $(".status_gate_card:checked").val();
            if (status_gate_card == 'Katu Akses Pengganti') {
                $('.textContainer').show();
            } else {
                $('.textContainer').hide();
            }

            $('body').on('change', ".status_gate_card", function (event) {
                let status_gate_card = $(".status_gate_card:checked").val();
                if (status_gate_card == 'Katu Akses Pengganti') {
                    $('.textContainer').show();
                } else {
                    $('.textContainer').hide();
                    $('.status_gate_card_text').val(null);
                }
            });
            
            let vehicle_type = $(".vehicle_type:checked").val();
            if (vehicle_type == 'Lainnya') {
                $('.textContainer2').show();
            } else {
                $('.textContainer2').hide();
            }

            $('body').on('change', ".vehicle_type", function (event) {
                let vehicle_type = $(".vehicle_type:checked").val();
                if (vehicle_type == 'Lainnya') {
                    $('.textContainer2').show();
                } else {
                    $('.textContainer2').hide();
                    $('.vehicle_type_text').val(null);
                }
            });

            let payment_source = $('#payment_source').val();
            if (payment_source == 'Bank Transfer (Perlu Konfirmasi Pembayaran Manual)') {
                $('#fileContainer').show();
            } else {
                $('#fileContainer').hide();
            }

            $('body').on('change', '#payment_source', function (event) {
                let payment_source = $('#payment_source').val();
                if (payment_source == 'Bank Transfer (Perlu Konfirmasi Pembayaran Manual)') {
                    $('#fileContainer').show();
                } else {
                    $('#fileContainer').hide();
                }
            });
        </script>
    @endpush
@endsection

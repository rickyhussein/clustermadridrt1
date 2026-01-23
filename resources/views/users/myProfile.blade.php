@extends('layouts.app')
@section('back')
    <a href="{{ url('/my-settings') }}" class="back-btn"> <i class="icon-left"></i> </a>
@endsection
@section('container')
    <form class="tf-form" action="{{ url('/my-profile/update/'.$user->id) }}" enctype="multipart/form-data" method="POST">
        @method('PUT')
        @csrf
        <div id="app-wrap" class="mt-4">
            <div class="bill-content">
                <div class="tf-container ms-4 me-4">
                    <div class="card-secton transfer-section mt-2">
                            <div class="tf-balance-box" style="background-color: rgb(207, 207, 207);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="inner-left d-flex justify-content-between align-items-center">
                                        @if($user->foto == null)
                                            <img src="{{ url('assets/img/foto_default.jpg') }}" alt="image">
                                        @else
                                            <img src="{{ url('/storage/'.$user->foto) }}" alt="image">
                                        @endif
                                        <p class="fw_7 on_surface_color">{{ $user->name }}</p>
                                    </div>
                                    <p class="fw_7 on_surface_color">{{ $user->alamat }}</p>
                                </div>
                            </div>
                            <div class="tf-spacing-20"></div>
                            <div class="group-input">
                                <label for="name">Nama</label>
                                <input type="text" class="@error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" />
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="email">Email</label>
                                <input type="email" class="@error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="@error('alamat') is-invalid @enderror" id="alamat" name="alamat" value="{{ old('alamat', $user->alamat) }}">
                                @error('alamat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="rt" style="z-index: 100">RT</label>
                                <select style="width: 100%" name="rt" id="rt" class="@error('rt') is-invalid @enderror select2" data-live-search="true">
                                    <option value="">-- Pilih RT --</option>
                                    <option value="001" {{ '001' == old('rt', $user->rt) ? 'selected="selected"' : '' }}>001</option>
                                </select>
                                @error('rt')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="rw" style="z-index: 100">RW</label>
                                <select style="width: 100%" name="rw" id="rw" class="@error('rw') is-invalid @enderror select2" data-live-search="true">
                                    <option value="">-- Pilih RW --</option>
                                    <option value="016" {{ '016' == old('rw', $user->rw) ? 'selected="selected"' : '' }}>016</option>
                                </select>
                                @error('rt')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="no_hp">Nomor HP</label>
                                <input type="text" class="@error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}">
                                @error('no_hp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" class="@error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" value="{{ old('keterangan', $user->keterangan) }}">
                                @error('keterangan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto">
                                @error('foto')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="group-input">
                                <label for="role">Role</label>
                                <input style="width: 100%" type="text" class="@error('role') is-invalid @enderror" name="role" value="{{ old('role', $user_roles) }}" disabled />
                            </div>

                            <div class="group-input">
                                <label style="z-index: 1000" for="status">status</label>
                                <input type="text" class="@error('status') is-invalid @enderror" name="status" value="{{ old('status', $user->status) }}" disabled />
                            </div>

                            @php
                                $old = session()->getOldInput();
                            @endphp
                            <div id="keluargaContainer">
                                @if(isset($old['nama_keluarga']))
                                    @foreach ($old['nama_keluarga'] as $key => $detailName)
                                        <div class="keluargaItem">
                                            <hr>
                                            <h3 class="text-center title">Anggota Keluarga {{ $key + 1 }}</h3>
                                            <br>
                                            <div class="group-input">
                                                <label for="nama_keluarga">Nama Keluarga</label>
                                                <input type="text" class="borderi nama_keluarga" name="nama_keluarga[]" value="{{ old('nama_keluarga')[$key] }}">
                                            </div>
                                            <div class="group-input">
                                                <label style="z-index: 1000" for="status_keluarga">Status Keluarga</label>
                                                <select style="width: 100%" class="borderi select2 status_keluarga" name="status_keluarga[]">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach(['Kepala Keluarga', 'Suami', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain', 'Pembantu', 'Lainnya'] as $status_keluarga)
                                                        <option value="{{ $status_keluarga }}" {{ $status_keluarga == old('status_keluarga')[$key] ? 'selected="selected"' : '' }}>{{ $status_keluarga }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label for="born_place">Tempat Lahir</label>
                                                <input type="text" class="borderi born_place" name="born_place[]" value="{{ old('born_place')[$key] }}">
                                            </div>
                                            <div class="group-input">
                                                <label for="born_date">Tanggal Lahir</label>
                                                <input type="text" class="date borderi born_date" name="born_date[]" value="{{ old('born_date')[$key] }}">
                                            </div>
                                            <div class="group-input">
                                                <label for="gender" style="z-index: 1000">Jenis Kelamin</label>
                                                <select style="width: 100%" name="gender[]" class="borderi gender select2">
                                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                                    <option value="Laki-Laki" {{ 'Laki-Laki' == old('gender')[$key] ? 'selected="selected"' : '' }}>Laki-Laki</option>
                                                    <option value="Perempuan" {{ 'Perempuan' == old('gender')[$key] ? 'selected="selected"' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label for="nation">Kewarganegaraan</label>
                                                <input type="text" class="borderi nation" name="nation[]" value="{{ old('nation')[$key] }}">
                                            </div>
                                            <div class="group-input">
                                                <label for="religion" style="z-index: 1000">Agama</label>
                                                <select style="width: 100%" name="religion[]" class="borderi religion select2">
                                                    <option value="">-- Pilih Agama --</option>
                                                    <option value="Islam" {{ 'Islam' == old('religion')[$key] ? 'selected="selected"' : '' }}>Islam</option>
                                                    <option value="Kristen Protestan" {{ 'Kristen Protestan' == old('religion')[$key] ? 'selected="selected"' : '' }}>Kristen Protestan</option>
                                                    <option value="Kristen Katolik" {{ 'Kristen Katolik' == old('religion')[$key] ? 'selected="selected"' : '' }}>Kristen Katolik</option>
                                                    <option value="Hindu" {{ 'Hindu' == old('religion')[$key] ? 'selected="selected"' : '' }}>Hindu</option>
                                                    <option value="Buddha" {{ 'Buddha' == old('religion')[$key] ? 'selected="selected"' : '' }}>Buddha</option>
                                                    <option value="Khonghucu" {{ 'Khonghucu' == old('religion')[$key] ? 'selected="selected"' : '' }}>Khonghucu</option>
                                                    <option value="Lainnya" {{ 'Lainnya' == old('religion')[$key] ? 'selected="selected"' : '' }}>Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label for="ktp_number">Nomor KTP</label>
                                                <input type="text" class="borderi ktp_number" name="ktp_number[]" value="{{ old('ktp_number')[$key] }}">
                                            </div>
                                            <div class="group-input">
                                                <label for="married_status" style="z-index: 1000">Status Perkawinan</label>
                                                <select style="width: 100%" name="married_status[]" class="borderi married_status select2">
                                                    <option value="">-- Pilih Pilih Status Perkawinan --</option>
                                                    <option value="Belum Kawin" {{ 'Belum Kawin' == old('married_status')[$key] ? 'selected="selected"' : '' }}>Belum Kawin</option>
                                                    <option value="Kawin" {{ 'Kawin' == old('married_status')[$key] ? 'selected="selected"' : '' }}>Kawin</option>
                                                    <option value="Cerai Hidup" {{ 'Cerai Hidup' == old('married_status')[$key] ? 'selected="selected"' : '' }}>Cerai Hidup</option>
                                                    <option value="Cerai Mati" {{ 'Cerai Mati' == old('married_status')[$key] ? 'selected="selected"' : '' }}>Cerai Mati</option>
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label for="job">Pekerjaan</label>
                                                <input type="text" class="borderi job" name="job[]" value="{{ old('job')[$key] }}">
                                            </div>
                                            <div class="group-input">
                                                <button class="tf-btn danger large delete">Delete</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @if (count($user->keluarga) > 0)
                                        @foreach ($user->keluarga as $key => $keluarga)
                                            <div class="keluargaItem">
                                                <hr>
                                                <h3 class="text-center title">Anggota Keluarga {{ $key + 1 }}</h3>
                                                <br>
                                                <div class="group-input">
                                                    <label for="nama_keluarga">Nama Keluarga</label>
                                                    <input type="text" class="borderi nama_keluarga" name="nama_keluarga[]" value="{{ $keluarga->nama_keluarga }}">
                                                </div>
                                                <div class="group-input">
                                                    <label style="z-index: 1000" for="status_keluarga">Status Keluarga</label>
                                                    <select style="width: 100%" class="borderi select2 status_keluarga" name="status_keluarga[]">
                                                        <option value="">-- Pilih --</option>
                                                        @foreach(['Kepala Keluarga', 'Suami', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain', 'Pembantu', 'Lainnya'] as $status_keluarga)
                                                            <option value="{{ $status_keluarga }}" {{ $status_keluarga == $keluarga->status_keluarga ? 'selected="selected"' : '' }}>{{ $status_keluarga }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="group-input">
                                                    <label for="born_place">Tempat Lahir</label>
                                                    <input type="text" class="borderi born_place" name="born_place[]"  value="{{ $keluarga->born_place }}">
                                                </div>
                                                <div class="group-input">
                                                    <label for="born_date">Tanggal Lahir</label>
                                                    <input type="text" class="date borderi born_date" name="born_date[]"  value="{{ $keluarga->born_date }}">
                                                </div>
                                                <div class="group-input">
                                                    <label for="gender" style="z-index: 1000">Jenis Kelamin</label>
                                                    <select style="width: 100%" name="gender[]" class="borderi gender select2">
                                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                                        <option value="Laki-Laki" {{ 'Laki-Laki' == $keluarga->gender ? 'selected="selected"' : '' }}>Laki-Laki</option>
                                                        <option value="Perempuan" {{ 'Perempuan' == $keluarga->gender ? 'selected="selected"' : '' }}>Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="group-input">
                                                    <label for="nation">Kewarganegaraan</label>
                                                    <input type="text" class="borderi nation" name="nation[]"  value="{{ $keluarga->nation }}">
                                                </div>
                                                <div class="group-input">
                                                    <label for="religion" style="z-index: 1000">Agama</label>
                                                    <select style="width: 100%" name="religion[]" class="borderi religion select2">
                                                        <option value="">-- Pilih Agama --</option>
                                                        <option value="Islam" {{ 'Islam' == $keluarga->religion ? 'selected="selected"' : '' }}>Islam</option>
                                                        <option value="Kristen Protestan" {{ 'Kristen Protestan' == $keluarga->religion ? 'selected="selected"' : '' }}>Kristen Protestan</option>
                                                        <option value="Kristen Katolik" {{ 'Kristen Katolik' == $keluarga->religion ? 'selected="selected"' : '' }}>Kristen Katolik</option>
                                                        <option value="Hindu" {{ 'Hindu' == $keluarga->religion ? 'selected="selected"' : '' }}>Hindu</option>
                                                        <option value="Buddha" {{ 'Buddha' == $keluarga->religion ? 'selected="selected"' : '' }}>Buddha</option>
                                                        <option value="Khonghucu" {{ 'Khonghucu' == $keluarga->religion ? 'selected="selected"' : '' }}>Khonghucu</option>
                                                        <option value="Lainnya" {{ 'Lainnya' == $keluarga->religion ? 'selected="selected"' : '' }}>Lainnya</option>
                                                    </select>
                                                </div>
                                                <div class="group-input">
                                                    <label for="ktp_number">Nomor KTP</label>
                                                    <input type="text" class="borderi ktp_number" name="ktp_number[]"  value="{{ $keluarga->ktp_number }}">
                                                </div>
                                                <div class="group-input">
                                                    <label for="married_status" style="z-index: 1000">Status Perkawinan</label>
                                                    <select style="width: 100%" name="married_status[]" class="borderi married_status select2">
                                                        <option value="">-- Pilih Pilih Status Perkawinan --</option>
                                                        <option value="Belum Kawin" {{ 'Belum Kawin' == $keluarga->married_status ? 'selected="selected"' : '' }}>Belum Kawin</option>
                                                        <option value="Kawin" {{ 'Kawin' == $keluarga->married_status ? 'selected="selected"' : '' }}>Kawin</option>
                                                        <option value="Cerai Hidup" {{ 'Cerai Hidup' == $keluarga->married_status ? 'selected="selected"' : '' }}>Cerai Hidup</option>
                                                        <option value="Cerai Mati" {{ 'Cerai Mati' == $keluarga->married_status ? 'selected="selected"' : '' }}>Cerai Mati</option>
                                                    </select>
                                                </div>
                                                <div class="group-input">
                                                    <label for="job">Pekerjaan</label>
                                                    <input type="text" class="borderi job" name="job[]"  value="{{ $keluarga->job }}">
                                                </div>
                                                <div class="group-input">
                                                    <button class="tf-btn danger large delete">Delete</button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="keluargaItem">
                                            <hr>
                                            <h3 class="text-center title">Anggota Keluarga 1</h3>
                                            <br>
                                            <div class="group-input">
                                                <label for="nama_keluarga">Nama Keluarga</label>
                                                <input type="text" class="borderi nama_keluarga" name="nama_keluarga[]">
                                            </div>
                                            <div class="group-input">
                                                <label style="z-index: 1000" for="status_keluarga">Status Keluarga</label>
                                                <select style="width: 100%" class="borderi select2 status_keluarga" name="status_keluarga[]">
                                                    <option value="">-- Pilih --</option>
                                                    @foreach(['Kepala Keluarga', 'Suami', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain', 'Pembantu', 'Lainnya'] as $status_keluarga)
                                                        <option value="{{ $status_keluarga }}">{{ $status_keluarga }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label for="born_place">Tempat Lahir</label>
                                                <input type="text" class="borderi born_place" name="born_place[]">
                                            </div>
                                            <div class="group-input">
                                                <label for="born_date">Tanggal Lahir</label>
                                                <input type="text" class="date borderi born_date" name="born_date[]">
                                            </div>
                                            <div class="group-input">
                                                <label for="gender" style="z-index: 1000">Jenis Kelamin</label>
                                                <select style="width: 100%" name="gender[]" class="borderi gender select2">
                                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                                    <option value="Laki-Laki">Laki-Laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label for="nation">Kewarganegaraan</label>
                                                <input type="text" class="borderi nation" name="nation[]">
                                            </div>
                                            <div class="group-input">
                                                <label for="religion" style="z-index: 1000">Agama</label>
                                                <select style="width: 100%" name="religion[]" class="borderi religion select2">
                                                    <option value="">-- Pilih Agama --</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                                    <option value="Kristen Katolik">Kristen Katolik</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Buddha">Buddha</option>
                                                    <option value="Khonghucu">Khonghucu</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label for="ktp_number">Nomor KTP</label>
                                                <input type="text" class="borderi ktp_number" name="ktp_number[]">
                                            </div>
                                            <div class="group-input">
                                                <label for="married_status" style="z-index: 1000">Status Perkawinan</label>
                                                <select style="width: 100%" name="married_status[]" class="borderi married_status select2">
                                                    <option value="">-- Pilih Status Perkawinan --</option>
                                                    <option value="Belum Kawin">Belum Kawin</option>
                                                    <option value="Kawin">Kawin</option>
                                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                                    <option value="Cerai Mati">Cerai Mati</option>
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label for="job">Pekerjaan</label>
                                                <input type="text" class="borderi job" name="job[]">
                                            </div>
                                            <div class="group-input">
                                                <button class="tf-btn danger large delete">Delete</button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <hr>
                            <br>
                            <button class="tf-btn success large addKeluarga">+ Tambah</button>
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
        <script>
            flatpickr(".date");

            $('.select2').select2();

            $('body').on('keyup', '#no_hp', function (event) {
                var val = $(this).val();
                if(isNaN(val)){
                    val = val.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                }
                $(this).val(val);
            });

            $('body').on('keyup', '.ktp_number', function (event) {
                var val = $(this).val();
                if(isNaN(val)){
                    val = val.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                }
                $(this).val(val);
            });

            $('.addKeluarga').click(function(e) {
                $("select.select2-hidden-accessible").select2('destroy');
                e.preventDefault();
                let keluargaCount = $('#keluargaContainer .keluargaItem').length + 1;
                let newKeluarga = `
                    <div class="keluargaItem">
                        <hr>
                        <h3 class="text-center title">Anggota Keluarga ${keluargaCount}</h3>
                        <br>
                        <div class="group-input">
                            <label for="nama_keluarga">Nama Keluarga</label>
                            <input type="text" class="borderi nama_keluarga" name="nama_keluarga[]">
                        </div>
                        <div class="group-input">
                            <label style="z-index: 1000" for="status_keluarga">Status Keluarga</label>
                            <select style="width: 100%" class="borderi select2 status_keluarga" name="status_keluarga[]">
                                <option value="">-- Pilih --</option>
                                @foreach(['Kepala Keluarga', 'Suami', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Famili Lain', 'Pembantu', 'Lainnya'] as $status_keluarga)
                                    <option value="{{ $status_keluarga }}">{{ $status_keluarga }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="group-input">
                            <label for="born_place">Tempat Lahir</label>
                            <input type="text" class="borderi born_place" name="born_place[]">
                        </div>
                        <div class="group-input">
                            <label for="born_date">Tanggal Lahir</label>
                            <input type="text" class="date borderi born_date" name="born_date[]">
                        </div>
                        <div class="group-input">
                            <label for="gender" style="z-index: 1000">Jenis Kelamin</label>
                            <select style="width: 100%" name="gender[]" class="borderi gender select2">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="group-input">
                            <label for="nation">Kewarganegaraan</label>
                            <input type="text" class="borderi nation" name="nation[]">
                        </div>
                        <div class="group-input">
                            <label for="religion" style="z-index: 1000">Agama</label>
                            <select style="width: 100%" name="religion[]" class="borderi religion select2">
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen Protestan">Kristen Protestan</option>
                                <option value="Kristen Katolik">Kristen Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="group-input">
                            <label for="ktp_number">Nomor KTP</label>
                            <input type="text" class="borderi ktp_number" name="ktp_number[]">
                        </div>
                        <div class="group-input">
                            <label for="married_status" style="z-index: 1000">Status Perkawinan</label>
                            <select style="width: 100%" name="married_status[]" class="borderi married_status select2">
                                <option value="">-- Pilih Status Perkawinan --</option>
                                <option value="Belum Kawin">Belum Kawin</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Cerai Hidup">Cerai Hidup</option>
                                <option value="Cerai Mati">Cerai Mati</option>
                            </select>
                        </div>
                        <div class="group-input">
                            <label for="job">Pekerjaan</label>
                            <input type="text" class="borderi job" name="job[]">
                        </div>
                        <div class="group-input">
                            <button class="tf-btn danger large delete">Delete</button>
                        </div>
                    </div>
                `;

                $('#keluargaContainer').append(newKeluarga);
                flatpickr(".date");
                $('.select2').select2();
            });

            $('#keluargaContainer').on('click', '.delete', function(e) {
                e.preventDefault();
                let keluargaCount = $('#keluargaContainer .keluargaItem').length + 1;

                if (keluargaCount == 2) {
                    alert('Cannot Delete First Row');
                } else {
                    if (confirm('Are you sure to delete this data')) {
                        const keluargaItem = $(this).closest('.keluargaItem');
                        keluargaItem.remove();
                        $('#keluargaContainer .keluargaItem').each(function(index) {
                            $(this).find('.title').text('Anggota Keluarga ' + (index + 1));
                        });
                    }
                }
            });
        </script>
    @endpush
@endsection

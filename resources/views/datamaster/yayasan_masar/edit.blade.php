<form action="{{ route('yayasan_masar.update', Crypt::encrypt($yayasan_masar->kode_yayasan)) }}" id="formcreateYayasanMasar" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="alert alert-info" role="alert">
        <i class="ti ti-info-circle me-2"></i>
        <strong>Kode Yayasan Masar:</strong> {{ $yayasan_masar->kode_yayasan }} (Auto-generated, tidak bisa diubah)
    </div>
    <x-input-with-icon-label icon="ti ti-credit-card" label="No. Identitas" name="no_identitas" value="{{ $yayasan_masar->no_identitas }}" />
    <x-input-with-icon-label icon="ti ti-user" label="Nama Yayasan Masar" name="nama" value="{{ $yayasan_masar->nama }}" />
    <div class="row">
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-map-pin" label="Tempat Lahir" name="tempat_lahir" value="{{ $yayasan_masar->tempat_lahir }}" />
        </div>
        <div class="col-6">
            <x-input-with-icon-label icon="ti ti-calendar" label="Tanggal Lahir" datepicker="flatpickr-date" name="tanggal_lahir"
                value="{{ $yayasan_masar->tanggal_lahir }}" />
        </div>
    </div>
    <x-textarea-label label="Alamat" name="alamat" value="{{ $yayasan_masar->alamat }}" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
            <option value="">Jenis Kelamin</option>
            <option value="L" {{ $yayasan_masar->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki - Laki</option>
            <option value="P" {{ $yayasan_masar->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <x-input-with-icon-label icon="ti ti-phone" label="No. HP" name="no_hp" value="{{ $yayasan_masar->no_hp }}" />
    <x-input-with-icon-label icon="ti ti-mail" label="Email" name="email" value="{{ $yayasan_masar->email }}" />
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-select-label label="Status Perkawinan" name="kode_status_kawin" :data="$status_kawin" key="kode_status_kawin" textShow="status_kawin"
                kode="true" selected="{{ $yayasan_masar->kode_status_kawin }}" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <div class="form-group mb-3">
                <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Pendidikan
                    Terakhir</label>
                <select name="pendidikan_terakhir" id="pendidikan_terakhir" class="form-select">
                    <option value="">Pendidikan Terakhir</option>
                    <option value="SD" {{ $yayasan_masar->pendidikan_terakhir == 'SD' ? 'selected' : '' }}>SD</option>
                    <option value="SMP" {{ $yayasan_masar->pendidikan_terakhir == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ $yayasan_masar->pendidikan_terakhir == 'SMA' ? 'selected' : '' }}>SMA</option>
                    <option value="SMK" {{ $yayasan_masar->pendidikan_terakhir == 'SMK"' ? 'selected' : '' }}>SMK</option>
                    <option value="D1" {{ $yayasan_masar->pendidikan_terakhir == 'D1' ? 'selected' : '' }}>D1</option>
                    <option value="D2" {{ $yayasan_masar->pendidikan_terakhir == 'D2' ? 'selected' : '' }}>D2</option>
                    <option value="D3" {{ $yayasan_masar->pendidikan_terakhir == 'D3' ? 'selected' : '' }}>D3</option>
                    <option value="D4" {{ $yayasan_masar->pendidikan_terakhir == 'D4' ? 'selected' : '' }}>D4</option>
                    <option value="S1" {{ $yayasan_masar->pendidikan_terakhir == 'S1' ? 'selected' : '' }}>S1</option>
                    <option value="S2" {{ $yayasan_masar->pendidikan_terakhir == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S3" {{ $yayasan_masar->pendidikan_terakhir == 'S3' ? 'selected' : '' }}>S3</option>
                </select>
            </div>
        </div>
    </div>
    <x-input-with-icon-label icon="ti ti-calendar" datepicker="flatpickr-date" label="Tanggal Masuk" name="tanggal_masuk"
        value="{{ $yayasan_masar->tanggal_masuk }}" />
    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Status Umroh</label>
        <select name="status_umroh" id="status_umroh" class="form-select">
            <option value="">Pilih Status Umroh</option>
            <option value="1" {{ $yayasan_masar->status_umroh == '1' ? 'selected' : '' }}>Umroh</option>
            <option value="0" {{ $yayasan_masar->status_umroh == '0' ? 'selected' : '' }}>Tidak</option>
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="exampleFormControlInput1" style="font-weight: 600" class="form-label">Status Aktif Yayasan Masar</label>
        <select name="status_aktif" id="status_aktif" class="form-select">
            <option value="">Status Aktif Yayasan Masar</option>
            <option value="1" {{ $yayasan_masar->status_aktif == '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ $yayasan_masar->status_aktif === '0' ? 'selected' : '' }}>Non Aktif</option>
        </select>
    </div>
    <x-input-file name="foto" label="Foto" />

    <x-input-with-icon icon="ti ti-fingerprint" label="PIN Finger Print" name="pin" value="{{ $yayasan_masar->pin }}" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>
<script src="{{ asset('assets/js/pages/karyawan.js') }}"></script>
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>

<script>
    $(function() {

        $(".flatpickr-date").flatpickr();
        // $('#nik_show').mask('00.00.000');
    });
</script>

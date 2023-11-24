<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute harus diterima.',
    'accepted_if' => ':attribute harus diterima saat :other adalah :value.',
    'active_url' => ':attribute bukan URL.',
    'after' => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => 'Hanya huruf yang diperbolehkan pada :attribute.',
    'alpha_dash' => 'Hanya huruf, angka, tanda hubung, dan tanda garis bawah yang diperbolehkan pada :attribute.',
    'alpha_num' => 'Hanya huruf dan angka yang diperbolehkan pada :attribute.',
    'array' => ':attribute harus berupa array.',
    'ascii' => 'Hanya simbol dan karakter alfanumerik 1 bit yang diperbolehkan pada :attribute.',
    'before' => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':attribute harus diantara :min sampai :max item.',
        'file' => 'Ukuran :attribute harus diantara :min sampai :max KB.',
        'numeric' => ':attribute harus diantara :min sampai :max.',
        'string' => 'Panjang :attribute harus diantara :min sampai :max karakter.',
    ],
    'boolean' => 'Kolom :attribute harus berupa true atau false.',
    'confirmed' => 'Kolom konfirmasi :attribute salah.',
    'current_password' => 'Password salah.',
    'date' => ':attribute bukan tanggal.',
    'date_equals' => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => ':attribute tidak sesuai format :format.',
    'decimal' => ':attribute harus memiliki :decimal angka desimal.',
    'declined' => ':attribute harus ditolak.',
    'declined_if' => ':attribute harus ditolak saat :other adalah :value.',
    'different' => ':attribute dan :other tidak boleh sama.',
    'digits' => ':attribute harus berupa :digits digit.',
    'digits_between' => ':attribute harus diantara :min sampai :max digit.',
    'dimensions' => ':attribute mengandung ukuran gambar atau foto yang salah.',
    'distinct' => 'Kolom :attribute mengandung nilai duplikat.',
    'doesnt_end_with' => ':attribute tidak boleh diakhiri dengan :values.',
    'doesnt_start_with' => ':attribute tidak boleh dimulai dengan :values.',
    'email' => ':attribute harus berupa alamat email.',
    'ends_with' => ':attribute harus diakhiri dengan :values.',
    'enum' => ':attribute yang dipilih salah.',
    'exists' => ':attribute salah atau tidak ditemukan.',
    'file' => ':attribute harus berupa file.',
    'filled' => 'Kolom :attribute harus diisi.',
    'gt' => [
        'array' => ':attribute harus lebih dari :value item.',
        'file' => 'Ukuran :attribute harus lebih besar dari :value KB.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string' => ':attribute harus lebih panjang dari :value karakter.',
    ],
    'gte' => [
        'array' => ':attribute harus memiliki :value item atau lebih.',
        'file' => 'Ukuran :attribute harus lebih besar dari atau sama dengan :value KB.',
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'string' => ':attribute harus lebih panjang dari atau sama dengan :value karakter.',
    ],
    'image' => ':attribute harus berupa gambar atau foto.',
    'in' => ':attribute yang dipilih salah.',
    'in_array' => 'Kolom :attribute tidak ada di :other.',
    'integer' => ':attribute harus berupa integer.',
    'ip' => ':attribute harus berupa alamat IP.',
    'ipv4' => ':attribute harus berupa alamat IPv4.',
    'ipv6' => ':attribute harus berupa alamat IPv6.',
    'json' => ':attribute harus berupa string JSON.',
    'lowercase' => ':attribute hrus berupa huruf kecil.',
    'lt' => [
        'array' => ':attribute harus kurang dari :value item.',
        'file' => 'Ukuran :attribute harus kurang dari :value KB.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string' => 'Panjang :attribute harus kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => ':attribute harus kurang dari atau sama dengan :value item.',
        'file' => 'Ukuran :attribute harus kurang dari atau sama dengan :value KB.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string' => 'Panjang :attribute harus kurang dari atau sama dengan :value karakter.',
    ],
    'mac_address' => ':attribute harus berupa alamat MAC.',
    'max' => [
        'array' => ':attribute maksimal :max item.',
        'file' => 'Ukuran :attribute maksimal :max KB.',
        'numeric' => ':attribute maksimal :max.',
        'string' => 'Panjang :attribute maksimal :max karakter.',
    ],
    'max_digits' => ':attribute tidak boleh lebih dari :max digit.',
    'mimes' => 'File :attribute harus berupa :values.',
    'mimetypes' => 'Tipe file :attribute harus berupa :values.',
    'min' => [
        'array' => ':attribute minimal :min item.',
        'file' => 'Ukuran :attribute minimal :min KB.',
        'numeric' => ':attribute minimal :min.',
        'string' => 'Panjang :attribute minimal :min karakter.',
    ],
    'min_digits' => ':attribute harus mengandung minimal :min digit.',
    'missing' => 'Kolom :attribute harus kosong.',
    'missing_if' => 'Kolom :attribute harus kosong saat :other adalah :value.',
    'missing_unless' => 'Kolom :attribute harus kosong kecuali :other adalah :value.',
    'missing_with' => 'Kolom :attribute harus kosong saat :values ada.',
    'missing_with_all' => 'Kolom :attribute harus kosong saat semua :values ada.',
    'multiple_of' => ':attribute harus berupa kelipatan dari :value.',
    'not_in' => ':attribute yang dipilih salah.',
    'not_regex' => 'Format :attribute salah.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => [
        'letters' => ':attribute harus mengandung minimal satu huruf.',
        'mixed' => ':attribute harus mengandung minimal satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute harus mengandung minimal satu angka.',
        'symbols' => ':attribute harus mengandung minimal satu simbol.',
        'uncompromised' => ':attribute yang diberikan terdaftar di daftar kebocoran data. Masukkan password lain.',
    ],
    'present' => 'Kolom :attribute harus ada.',
    'prohibited' => 'Kolom :attribute tidak diperbolehkan.',
    'prohibited_if' => 'Kolom :attribute tidak diperbolehkan saat :other adalah :value.',
    'prohibited_unless' => 'Kolom :attribute tidak diperbolehkan kecuali :other ada di :values.',
    'prohibits' => 'Kolom :attribute melarang :other terisi.',
    'regex' => 'Format :attribute salah.',
    'required' => 'Kolom :attribute diperlukan.',
    'required_array_keys' => 'Kolom :attribute harus mengandung entri untuk :values.',
    'required_if' => 'Kolom :attribute diperlukan saat :other adalah :value.',
    'required_if_accepted' => 'Kolom :attribute diperlukan saat :other diterima.',
    'required_unless' => 'Kolom :attribute diperlukan kecuali :other ada di :values.',
    'required_with' => 'Kolom :attribute diperlukan saat :values ada.',
    'required_with_all' => 'Kolom :attribute diperlukan saat semua :values ada.',
    'required_without' => 'Kolom :attribute diperlukan saat :values tidak ada.',
    'required_without_all' => 'Kolom :attribute diperlukan saat tidak ada :values yang ada.',
    'same' => ':attribute dan :other harus sama.',
    'size' => [
        'array' => ':attribute harus mengandung :size item.',
        'file' => 'Ukuran :attribute harus :size KB.',
        'numeric' => ':attribute harus :size.',
        'string' => 'Panjang :attribute harus :size karakter.',
    ],
    'starts_with' => ':attribute harus dimulai dengan: :values.',
    'string' => ':attribute harus berupa string.',
    'timezone' => ':attribute harus berupa zona waktu.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => ':attribute gagal diupload.',
    'uppercase' => ':attribute harus berupa huruf besar.',
    'url' => ':attribute harus berupa URL.',
    'ulid' => ':attribute harus berupa ULID.',
    'uuid' => ':attribute harus berupa UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];

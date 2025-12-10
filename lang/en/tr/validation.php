<?php

return [
    'accepted' => ':attribute kabul edilmelidir.',
    'active_url' => ':attribute geçerli bir URL olmalıdır.',
    'after' => ':attribute değeri :date tarihinden sonra olmalıdır.',
    'alpha' => ':attribute sadece harflerden oluşmalıdır.',
    'array' => ':attribute dizi olmalıdır.',
    'before' => ':attribute değeri :date tarihinden önce olmalıdır.',
    'between' => [
        'numeric' => ':attribute :min - :max arasında olmalıdır.',
        'file' => ':attribute :min - :max kilobayt aralığında olmalıdır.',
        'string' => ':attribute :min - :max karakter aralığında olmalıdır.',
    ],
    'confirmed' => ':attribute tekrarı eşleşmiyor.',
    'date' => ':attribute geçerli bir tarih olmalıdır.',
    'email' => ':attribute formatı geçersiz.',
    'exists' => 'Seçilen :attribute geçersiz.',
    'image' => ':attribute resim dosyası olmalıdır.',
    'integer' => ':attribute rakam olmalıdır.',
    'max' => [
        'numeric' => ':attribute değeri :max değerinden küçük olmalıdır.',
        'string' => ':attribute değeri en fazla :max karakter olmalıdır.',
    ],
    'min' => [
        'numeric' => ':attribute değeri en az :min değerinde olmalıdır.',
        'string' => ':attribute değeri en az :min karakter olmalıdır.',
    ],
    'numeric' => ':attribute sayı olmalıdır.',
    'required' => ':attribute alanı zorunludur.',
    'unique' => ':attribute daha önceden kayıt edilmiş.',
    'url' => ':attribute formatı geçersiz.',

    /*
    |--------------------------------------------------------------------------
    | Özelleştirilmiş Alan İsimleri
    |--------------------------------------------------------------------------
    | Buraya veritabanındaki sütun adlarının Türkçe karşılıklarını yazıyoruz.
    */
    'attributes' => [
        'name' => 'İsim / Başlık',
        'email' => 'E-Posta',
        'password' => 'Şifre',
        'phone' => 'Telefon',
        'tc_no' => 'TC Kimlik No',
        'check_in_date' => 'Giriş Tarihi',
        'check_out_date' => 'Çıkış Tarihi',
        'capacity' => 'Kapasite',
        'type' => 'Tür',
        'role' => 'Rol',
        'first_name' => 'Ad',
        'last_name' => 'Soyad',
    ],
];